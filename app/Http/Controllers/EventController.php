<?php

// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\MemberCredential;
use App\Models\MemberApplication;
use App\Models\Notification;
use App\Models\BeneficiaryAttendance;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Google_Client;  
use Google_Service_Calendar;  
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;

class EventController extends Controller
{
    public function showAdmin($id)
    {
        $event = Event::where('id', $id)->firstOrFail();
        $partners = DB::table('tblpartner')->pluck('partner_name', 'id');
        return view('admin.eventDetails', compact('event', 'partners'));
    }
    public function index()
    {
        $events = Event::take(3)->get(); // Fetch all events from the table
        return view('index', compact('events')); // Pass events to the view
    }

    //Query Event ID based on google calendar id
    public function getEventIdByGoogleId($googleEventId)
    {
        $event = DB::table('tblevent')->where('google_event_id', $googleEventId)->first();
        \Log::info('Event found using Google event id:', ['events' => $event]);
        if ($event) {
            return response()->json(['db_event_id' => $event ? $event->id : null]);
        } else {
            return response()->json(['error' => 'Event not found'], 404);
        }  
    }

    public function storeEvent(Request $request)
    {
        // Check if the request is from JavaScript 
        $isJavaScriptRequest = $request->header('X-Custom-Header') === 'JavaScriptRequest';
        \Log::info('Request Headers:', $request->headers->all());
        // Validate input
        $request->validate([
            'ename' => 'required|string|max:255',
            'etype' => 'required|string|max:255',
            'edesc' => 'required|string',
            'slots' => 'required|integer',
            'edate' => 'required|date',
            'timeStart' => 'required',
            'timeEnd' => 'required',
            'elocation' => 'required|string|max:255',
            'epartner' => 'required|string|max:255',
        ]);
    
        DB::beginTransaction();
    
        try {
            // Format start and end DateTime to ISO 8601 format
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->edate . ' ' . $request->timeStart)->format(\DateTime::ATOM);
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->edate . ' ' . $request->timeEnd)->format(\DateTime::ATOM);
    
            // Create Google Calendar Event
            $googleEvent = new Google_Service_Calendar_Event([
                'summary' => $request->ename,
                'location' => $request->elocation,
                'description' => $request->edesc,
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => 'Asia/Manila',
                ],
                'end' => [
                    'dateTime' => $endDateTime,
                    'timeZone' => 'Asia/Manila',
                ],
            ]);
    
            // Initialize Google Client
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google-calendar/service-account.json'));
            $client->addScope(Google_Service_Calendar::CALENDAR);
    
            $service = new Google_Service_Calendar($client);
    
            // Define Calendar ID and Insert Event
            $calendarId = '5f60ab0fe80f5fb52256c5858400e342e17127ac613eee2f37d19aed2bff487a@group.calendar.google.com';
            $createdGoogleEvent = $service->events->insert($calendarId, $googleEvent);
    
            // Store event in the database with the google_event_id
            $event = Event::create([
                'google_event_id' => $createdGoogleEvent->id,
                'title' => $request->ename,
                'start' => $request->timeStart,
                'end' => $request->timeEnd,
                'event_date' => $request->edate,
                'description' => $request->edesc,
                'number_of_volunteers' => $request->slots,
                'event_location' => $request->elocation,
                'partner' => $request->epartner,
                'category' => $request->etype,
                'event_status' => 'upcoming', // Assuming a default status
            ]);
    
            \Log::info('Event stored with Google event ID:', ['google_event_id' => $event->google_event_id]);
            //Send notifications only after the event is successfully created
            $volunteers = MemberCredential::all();
            foreach ($volunteers as $volunteer) {
                Notification::create([
                    'user_id' => $volunteer->memberCredentialsID, // Assuming this is the correct field for volunteer ID
                    'user_type' => MemberCredential::class, 
                    'type' => 'New Volunteer Event',
                    'title' => 'New Volunteer Event Scheduled',
                    'body' => 'A new Volunteer Event has been posted: ' . $event->title . '.',
                    'url' => route('volunteer.eventDetails', $event->id), // Assuming a route to event details
                    'is_read' => false,
                ]);
            }
            // Commit transaction
            DB::commit();
    
            // Return response based on request type
            if ($isJavaScriptRequest) {
                // For AJAX requests (e.g., calendar)
                \Log::info('Returning JSON success response for javaScript request.');
                return response()->json(['success' => true, 'event' => $event]);
            } else {
                // For traditional requests (e.g., dashboard)
                \Log::info('Returning redirect response for traditional request.');
                return redirect()->route('admin.event')->with('success', 'Event created successfully!');
            }
    
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to create Google calendar event', ['error' => $e->getMessage()]);
            // Return error response
            if ($request->ajax()) {
                // For AJAX requests, return JSON error
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to create event in Google Calendar: ' . $e->getMessage()
                ], 500);
            } else {
                // For traditional requests, redirect back with error
                return redirect()->back()->with('error', 'Failed to create event in Google Calendar.');
            }
        }
    }

    //Update function in Event Dashboard
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'ename' => 'required|string|max:255',
            'etype' => 'required|string|max:255',
            'edesc' => 'required|string',
            'slots' => 'required|integer',
            'edate' => 'required|date',
            'timeStart' => 'required',
            'timeEnd' => 'required',
            'elocation' => 'required|string|max:255',
            'epartner' => 'required|string|max:255',
        ]);
        // Log the raw input to understand its structure
        Log::info('Raw Request Data:', $request->all()); 

        // Find the event to update
        $event = Event::findOrFail($id);
    
        // Check if the event has a Google Calendar ID
        if ($event->google_event_id) {
            try {
                $client = new Google_Client();
                $client->setAuthConfig(storage_path('app/google-calendar/service-account.json'));
                $client->addScope(Google_Service_Calendar::CALENDAR);
    
                $service = new Google_Service_Calendar($client);
    
                // Define Calendar ID
                $calendarId = '5f60ab0fe80f5fb52256c5858400e342e17127ac613eee2f37d19aed2bff487a@group.calendar.google.com';
    
                // Retrieve the Google Calendar event to update
                $googleEvent = $service->events->get($calendarId, $event->google_event_id);
    
                // Update the Google Calendar event details
                $googleEvent->setSummary($request->ename);
                $googleEvent->setDescription($request->edesc);
                $googleEvent->setLocation($request->elocation);
    
                // Trim any extra whitespace from date and time fields
                $edate = trim($request->edate);
                $timeStart = trim($request->timeStart);
                $timeEnd = trim($request->timeEnd);
    
                // Check and log if we are getting the correct string values
                Log::info('Parsed Date and Time:', ['edate' => $edate, 'timeStart' => $timeStart, 'timeEnd' => $timeEnd]);

                // Now, proceed with the Carbon parsing and Google Calendar update
                $startDateTime = Carbon::parse("$edate $timeStart")->format(\DateTime::ATOM);
                $endDateTime = Carbon::parse("$edate $timeEnd")->format(\DateTime::ATOM);
    
                // Set start and end times for the Google Calendar event
                $start = new Google_Service_Calendar_EventDateTime();
                $start->setDateTime($startDateTime);
                $start->setTimeZone('Asia/Manila');
                $googleEvent->setStart($start);
    
                $end = new Google_Service_Calendar_EventDateTime();
                $end->setDateTime($endDateTime);
                $end->setTimeZone('Asia/Manila');
                $googleEvent->setEnd($end);

                Log::info('Date:', ['edate' => $edate]);
                Log::info('Start Time:', ['timeStart' => $timeStart]);
                Log::info('End Time:', ['timeEnd' => $timeEnd]);


                // Save the updated event to Google Calendar
                $service->events->update($calendarId, $event->google_event_id, $googleEvent);
    
                // If Google Calendar update is successful, update the event details in the database
                $event->update([
                    'title' => $request->ename,
                    'start' => $timeStart,
                    'end' => $timeEnd,
                    'event_date' => $edate,
                    'description' => $request->edesc,
                    'number_of_volunteers' => $request->slots,
                    'event_location' => $request->elocation,
                    'partner' => $request->epartner,
                    'category' => $request->etype,
                    'event_status' => 'upcoming', // Assuming a default status
                ]);
            } catch (\Google_Service_Exception $e) {
                Log::error('Google Calendar update failed: ' . $e->getMessage());
                return redirect()->route('admin.event')->with('error', 'Failed to update the event on Google Calendar: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('admin.event')->with('error', 'Google Calendar ID not found for this event.');
        }
    
        // Redirect to the event list with a success message
        return redirect()->route('admin.event')->with('success', 'Event updated successfully!');
    }

    //Delete function in Event Dashboard
    public function destroy($id)
    {
        $event = Event::where('id', $id)->firstOrFail();
    
        // Check if the event has a Google Calendar ID
        if ($event->google_event_id) {
            try {
                $client = new Google_Client();
                $client->setAuthConfig(storage_path('app/google-calendar/service-account.json'));
                $client->addScope(Google_Service_Calendar::CALENDAR);
    
                $service = new Google_Service_Calendar($client);
                $calendarId = '5f60ab0fe80f5fb52256c5858400e342e17127ac613eee2f37d19aed2bff487a@group.calendar.google.com';

                // Delete the event from Google Calendar
                $service->events->delete($calendarId, $event->google_event_id);
            } catch (\Google_Service_Exception $e) {
                Log::error('Google Calendar deletion failed: ' . $e->getMessage());
                return redirect()->route('admin.event')->with('error', 'Failed to delete the event from Google Calendar: ' . $e->getMessage());
            }
        }
    
        // Delete the event from the database
        $event->delete();
    
        return redirect()->route('admin.event')->with('success', 'Event deleted successfully.');
    }

    //Event Table and filter query function in Event Dashboard
    public function eventDashboard(Request $request)
    {
        // Handle the case where no user is authenticated
        $user = $this->currentUser();
        if (!$user) {
            // Check which guard should redirect the user
            if (\Auth::guard('admin')->viaRemember() || \Auth::guard('admin')->guest()) {
                // Redirect admin users
                return redirect()->route('admin.signin')->with('error', 'Your session has expired. Please log in again.');
            } elseif (\Auth::guard('web')->viaRemember() || \Auth::guard('web')->guest()) {
                // Redirect volunteer users
                return redirect()->route('volunteer.signin')->with('error', 'Your session has expired. Please log in again.');
            }
        }
        // Start a query on the Event model
        $query = Event::query();

        // Apply event type filter
        if ($request->filled('etype')) {
            $query->where('category', $request->etype);
        }

        // Apply date range filter
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('event_date', [$request->date_start, $request->date_end]);
        }

        // Apply volunteer slots filter (minimum slots)
        if ($request->filled('volunteers')) {
            $query->where('number_of_volunteers', '>=', $request->volunteers);
        }

        // Order events by event_date in descending order
        $query->orderBy('event_date', 'desc');

        // Get the filtered events
        $events = $query->paginate(5); 
        
        $partners = Partner::all();
        // Return the view with the filtered events
        return view('admin.event', compact('events', 'partners'));
    }

    public function showEventDetails($id)
    {
        // Fetch the event details
    $event = Event::findOrFail($id);

    // Check if the user is logged in as a volunteer
    if (Auth::guard('web')->check()) {
        // Get the logged-in volunteer's ID
        $volunteerID = Auth::guard('web')->user()->memberCredentialsID;

        // Check if the volunteer has already joined the event
        $hasJoined = Participant::where('eventID', $id)
                                ->where('memberCredentialsID', $volunteerID)
                                ->exists();

        // Render the volunteer-specific event details view
        return view('volunteer.eventDetails', compact('event', 'hasJoined'));
    } else {
        // Render the public view without volunteer-specific data
        return view('eventDetails', compact('event'));
    }
    }

    public function showEventParticipants($id)
    {
        // Fetch the event details
        $event = Event::findOrFail($id);
        // Fetch the participants with their volunteer details
        $participants = Participant::with('volunteer')->where('eventID', $id)->get();

        // Pass the event details and participants
        return view('admin.eventView', compact('event', 'participants'));
    }

    //Volunteer Join Event Function
    public function join(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::guard('web')->user();
        $volunteerID = $user->memberCredentialsID;

        // Check if the volunteer has already joined this event
        $alreadyJoined = Participant::where('eventID', $event->id)
                                    ->where('memberCredentialsID', $volunteerID)
                                    ->exists();

        // Use the memberApplicationID to get the course from tblmemberapplication
        $application = MemberApplication::where('memberApplicationID', $user->memberApplicationID)->firstOrFail();

        if ($alreadyJoined) {
            return response()->json(['success' => false, 'message' => 'You have already joined this event.'], 400);
        }

        // Ensure there are available spots
        if ($event->volunteers_joined >= $event->number_of_volunteers) {
            return response()->json(['success' => false, 'message' => 'No more spots available.'], 400);
        }

        // Add participant
        $participant = Participant::create([
            'memberCredentialsID' => $volunteerID,
            'eventID' => $event->id,
        ]);

        // Increment the volunteers joined count
        $event->volunteers_joined += 1;
        $event->save();

        // Prepare QR code data
        $qrData = [
            'Event' => $event->id,
            'Participants ID' => $participant->participantsID,
            'Full Name' => $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name,
            'Course' => $application->course,
            'Student ID' => $user->studentID,
        ];

        // Generate QR code with the volunteer's details and event name
        $qrCodeImage = QrCode::format('png')
                            ->size(250)
                            ->generate(json_encode($qrData));

        // Store the QR code image (optional)
        $fileName = 'qr_codes/volunteer_'.$volunteerID.'_event_'.$event->id.'.png';
        Storage::disk('public')->put($fileName, $qrCodeImage);

        return response()->json([
            'success' => true,
            'remaining_volunteers' => $event->number_of_volunteers - $event->volunteers_joined,
            'qr_code' => Storage::url($fileName),  // Return the QR code file URL
        ]);
    }
    // Function to regenerate QrCode
    public function regenerateQrCode($id)
    {
        // Log the start of the QR code regeneration process
        Log::info("Regenerating QR code for event ID: {$id}");
    
        $event = Event::findOrFail($id);
    
        // Log if the event is found
        Log::info("Event found: {$event->name}");
    
        // Check if the volunteer has joined the event
        $user = Auth::guard('web')->user();
        $volunteerID = $user->memberCredentialsID;
    
        $participant = Participant::where('eventID', $event->id)
            ->where('memberCredentialsID', $volunteerID)
            ->first();
    
        if (!$participant) {
            Log::warning("Volunteer ID: {$volunteerID} has not joined event ID: {$event->id}");
            return response()->json(['success' => false, 'message' => 'You have not joined this event.'], 403);
        }
    
        // Log the participant's information
        Log::info("Participant found: ID {$participant->participantsID} for Volunteer ID: {$volunteerID}");
    
        // Use the memberApplicationID to get the course from tblmemberapplication
        $application = MemberApplication::where('memberApplicationID', $user->memberApplicationID)->firstOrFail();
    
        // Log the application details
        Log::info("Application found: Course - {$application->course}, Student ID - {$user->studentID}");
    
        // Prepare QR code data
        $qrData = [
            'Event' => $event->id,
            'Participants ID' => $participant->participantsID,
            'Full Name' => $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name,
            'Course' => $application->course,
            'Student ID' => $user->studentID,
        ];
    
        // Log the QR code data
        Log::info("QR Code data: " . json_encode($qrData));
    
        // Generate QR code with the volunteer's details and event name
        $qrCodeImage = QrCode::format('png')
                            ->size(250)
                            ->generate(json_encode($qrData));
    
        // Use the same filename as in the join method
        $fileName = 'qr_codes/volunteer_'.$volunteerID.'_event_'.$event->id.'.png';
    
        // Store the QR code image (overwrite if it exists)
        Storage::disk('public')->put($fileName, $qrCodeImage);
    
        // Log the QR code storage
        Log::info("QR code for event ID: {$event->id} and volunteer ID: {$volunteerID} stored at: {$fileName}");
    
        return response()->json([
            'success' => true,
            'qr_code' => Storage::url($fileName), // Return the QR code file URL
        ]);
    }

    //return events the volunteer has joined in
    public function volunteerEvents(Request $request)
    {
        // Get the authenticated volunteer's ID
        if (Auth::guard('web')->check()) {
            $volunteerId = Auth::guard('web')->user()->memberCredentialsID;
        } else {
            return redirect()->route('volunteer.signin')->with('error', 'You need to be logged in to access this page.');
        }
        // Determine sort order based on user input, default to most recent
        $sort = $request->input('sort', 'recent') === 'recent' ? 'desc' : 'asc';
    
        // Join with events table and sort based on event_date
        $events = Participant::where('memberCredentialsID', $volunteerId)
            ->join('tblevent', 'tblparticipants.eventID', '=', 'tblevent.id')
            ->orderBy('tblevent.event_date', $sort)
            ->select('tblparticipants.*', 'tblevent.id', 'tblevent.title', 'tblevent.description', 'tblevent.category', 
                     'tblevent.event_date', 'tblevent.start', 'tblevent.end', 'tblevent.event_location')
            ->paginate(9);
    
        return view('volunteer.joinedEvents', compact('events'));
    }   
    function currentUser() {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } elseif (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }
        return null;
    }
}

