<?php

// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\MemberApplication;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function showAdmin($id)
    {
        $event = Event::where('id', $id)->firstOrFail();
        return view('admin.eventDetails', compact('event'));
    }
    public function index()
    {
        $events = Event::all()->get(4); // Fetch all events from the table
        return view('index', compact('events')); // Pass events to the view
    }
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


    public function destroy($id)
    {
        $event = Event::where('id', $id)->firstOrFail();;
        $event->delete();

        return redirect()->route('admin.event')->with('success', 'Event deleted successfully.');
    }

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
        $events = $query->get();

        // Return the view with the filtered events
        return view('admin.event', compact('events'));
    }

    public function showEventDetails($id)
    {
        // Fetch the event details
        $event = Event::findOrFail($id);

        // Get the logged-in volunteer's ID
        $volunteerID = Auth::guard('web')->user()->memberCredentialsID;

        // Check if the volunteer has already joined the event
        $hasJoined = Participant::where('eventID', $id)
                                ->where('memberCredentialsID', $volunteerID)
                                ->exists();

        // Pass the event details and hasJoined flag to the view
        return view('volunteer.eventDetails', compact('event', 'hasJoined'));
    }
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
        Participant::create([
            'memberCredentialsID' => $volunteerID,
            'eventID' => $event->id,
        ]);

        // Increment the volunteers joined count
        $event->volunteers_joined += 1;
        $event->save();

        // Prepare QR code data
        $qrData = [
            'Full Name' => $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name,
            'Course' => $application->course,
            'Student ID' => $user->schoolID,
            'Event' => $event->id,
            'Event_name' => $event->title
        ];

        // Generate QR code with the volunteer's details and event name
        $qrCodeImage = QrCode::format('png')
                            ->size(300)
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
}

