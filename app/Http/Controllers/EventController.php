<?php

// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function viewCreateEvent()
    {
        return view('admin.createEvent');
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
            'time_range' => $request->timeStart . ' - ' . $request->timeEnd, // Assuming a time range column
        ]);

        // Redirect to a success page or back to the form with a success message
        return redirect()->back()->with('success', 'Event created successfully!');
    }
}

