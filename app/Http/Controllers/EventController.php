<?php

// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function viewCreateEvent()
    {
        return view('admin.createEvent');
    }
    public function showAdmin($id)
    {
        $event = Event::where('eventID', $id)->firstOrFail();
        return view('admin.eventDetails', compact('event'));
    }
    public function index()
    {
        $events = Event::all(); // Fetch all events from the table
        return view('index', compact('events')); // Pass events to the view
    }

    public function store(Request $request)
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
            'event_name' => $request->ename,
            'event_start' => $request->timeStart,
            'event_end' => $request->timeEnd,
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

    // app/Http/Controllers/EventController.php
    public function update(Request $request, $id)
    {
        $event = Event::where('eventID', $id)->firstOrFail();;

        $event->update([
            'event_name' => $request->ename,
            'category' => $request->etype,
            'description' => $request->edesc,
            'number_of_volunteers' => $request->slots,
            'event_date' => $request->edate,
            'event_start' => $request->timeStart,
            'event_end' => $request->timeEnd,
            'event_location' => $request->elocation,
            // 'partners' => $request->epartner,
        ]);

        return redirect()->route('admin.eventDetails', ['id' => $id])->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::where('eventID', $id)->firstOrFail();;
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

    public function showEventDetails($eventID)
    {
        // Fetch the event details
        $event = Event::findOrFail($eventID);

        // Get the logged-in volunteer's ID
        $volunteerID = Auth::guard('web')->user()->memberCredentialsID;

        // Check if the volunteer has already joined the event
        $hasJoined = Participant::where('eventID', $eventID)
                                ->where('memberCredentialsID', $volunteerID)
                                ->exists();

        // Pass the event details and hasJoined flag to the view
        return view('volunteer.eventDetails', compact('event', 'hasJoined'));
    }
    public function join(Request $request, $eventID)
    {
        $event = Event::findOrFail($eventID);
        $user = Auth::guard('web')->user();
        $volunteerID = $user->memberCredentialsID;

        // Check if the volunteer has already joined this event
        $alreadyJoined = Participant::where('eventID', $event->eventID)
                                    ->where('memberCredentialsID', $volunteerID)
                                    ->exists();

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
            'eventID' => $event->eventID,
        ]);

        // Increment the volunteers joined count
        $event->volunteers_joined += 1;
        $event->save();

        return response()->json([
            'success' => true,
            'remaining_volunteers' => $event->number_of_volunteers - $event->volunteers_joined,
        ]);
    }
}

