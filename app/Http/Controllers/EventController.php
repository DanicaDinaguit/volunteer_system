<?php

// app/Http/Controllers/EventController.php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        
        // if (request()->wantsJson()) {
            return response()->json($events);
        // }
        
        // return view('index', compact('events'));
    }
    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->update($request->all());
            return response()->json($event);
        }
        return response()->json(['error' => 'Event not found'], 404);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['error' => 'Event not found'], 404);
    }
    
}

