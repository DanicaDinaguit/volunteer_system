<?php

// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\MemberCredential;
use App\Models\MemberApplication;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Google_Client;  
use Google_Service_Calendar;  
use Google_Service_Calendar_Event;

class EventController extends Controller
{
    public function showAdmin($id)
    {
        $event = Event::where('id', $id)->firstOrFail();
        return view('admin.eventDetails', compact('event'));
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

    //Create Event function for event dashboard
    // public function store(Request $request)
    // {
    //     // Validate the request data
    //     $request->validate([
    //         'ename' => 'required|string|max:255',
    //         'etype' => 'required|string|max:255',
    //         'edesc' => 'required|string',
    //         'slots' => 'required|integer',
    //         'edate' => 'required|date',
    //         'timeStart' => 'required',
    //         'timeEnd' => 'required',
    //         'elocation' => 'required|string|max:255',
    //         'epartner' => 'required|string|max:255',
    //     ]);
    
    //     DB::beginTransaction(); // Start a database transaction
    
    //     try {
    //         // Create a new event
    //         $event = Event::create([
    //             'title' => $request->ename,
    //             'start' => $request->timeStart,
    //             'end' => $request->timeEnd,
    //             'event_date' => $request->edate,
    //             'description' => $request->edesc,
    //             'number_of_volunteers' => $request->slots,
    //             'event_location' => $request->elocation,
    //             'category' => $request->etype,
    //             'event_status' => 'upcoming', // Assuming a default status
    //         ]);
    
    //         // Send notifications only after the event is successfully created
    //         $volunteers = MemberCredential::all();
    //         foreach ($volunteers as $volunteer) {
    //             Notification::create([
    //                 'user_id' => $volunteer->memberCredentialsID, // Assuming this is the correct field for volunteer ID
    //                 'user_type' => MemberCredential::class, 
    //                 'type' => 'New Volunteer Event',
    //                 'title' => 'New Volunteer Event Scheduled',
    //                 'body' => 'A new Volunteer Event has been posted: ' . $event->title . '.',
    //                 'url' => route('volunteer.eventDetails', $event->id), // Assuming a route to event details
    //                 'is_read' => false,
    //             ]);
    //         }
    
    //         DB::commit(); // Commit transaction if everything is successful
    
    //         // Redirect back with a success message
    //         return redirect()->back()->with('success', 'Event created successfully and notifications sent!');
        
    //     } catch (\Exception $e) {
    //         DB::rollBack(); // Rollback the transaction in case of any errors
    //         // Log or handle the error as needed
    //         return redirect()->back()->withErrors('Error creating event: ' . $e->getMessage());
    //     }
    // }
    public function storeEvent(Request $request)
    {
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
                'category' => $request->etype,
                'event_status' => 'upcoming', // Assuming a default status
            ]);
    
            \Log::info('Event stored with Google event ID:', ['google_event_id' => $event->google_event_id]);
    
            // Commit transaction
            DB::commit();
    
            // Return success response
            return response()->json(['success' => true, 'event' => $event]);
    
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to create Google calendar event', ['error' => $e->getMessage()]);
            // Return error response
            return response()->json([
                'success' => false,
                'error' => 'Failed to create event in Google Calendar: ' . $e->getMessage()
            ], 500);
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

        // Create a new event
        Event::create([
            'title' => $request->ename,
            'start' => $request->timeStart,
            'end' => $request->timeEnd,
            'event_date' => $request->edate,
            'description' => $request->edesc,
            'number_of_volunteers' => $request->slots,
            'event_location' => $request->elocation,
            'category' => $request->etype,
            'event_status' => 'upcoming', // Assuming a default status
        ]);

        // Redirect to a success page or back to the form with a success message
        return redirect()->back()->with('success', 'Event created successfully!');
    }

    //Delete function in Event Dashboard
    public function destroy($id)
    {
        $event = Event::where('id', $id)->firstOrFail();;
        $event->delete();

        return redirect()->route('admin.event')->with('success', 'Event deleted successfully.');
    }

    //Event Table and filter query function in Event Dashboard
    public function eventDashboard(Request $request)
    {
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

        // Get the filtered events
        $events = $query->paginate(5); 

        // Return the view with the filtered events
        return view('admin.event', compact('events'));
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

        // Pass the event details and participants to the view
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
    
    //return events the volunteer has joined in
    public function volunteerEvents(Request $request)
    {
        // Get the authenticated volunteer's ID
        $volunteerId = Auth::guard('web')->user()->memberCredentialsID;
    
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
    
}

