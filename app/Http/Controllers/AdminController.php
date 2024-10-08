<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\MemberApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Google_Client;  
use Google_Service_Calendar;  
use Google_Service_Calendar_Event;

class AdminController extends Controller
{
    public function calendar()
    {
        $googleCalendarController = new GoogleCalendarController();
        $response = $googleCalendarController->fetchEvents();
        $events = json_decode($response->getContent(), true);
        return view('admin.calendar', compact('events'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'volunteers_needed' => 'nullable|integer',
            'epartner' => 'nullable|string|max:255',
        ]);

        $event = Event::create([
            'title' => $request->name,  
            'category' => $request->input('type'),    
            'description' => $request->description,
            'event_start' => $request->start_time,  
            'event_end' => $request->end_time,      
            'event_location' => $request->location, 
            'number_of_volunteers' => $request->volunteers_needed,
            'partnership' => $request->epartner,
            'event_date' => date('Y-m-d', strtotime($request->start_time)), 
            'event_status' => 'Scheduled', 
        ]);

        DB::beginTransaction();

        try {
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google-calendar/service-account.json'));
            $client->addScope(Google_Service_Calendar::CALENDAR);

            $service = new Google_Service_Calendar($client);

            $googleEvent = new Google_Service_Calendar_Event([
                'summary' => $request->name,
                'location' => $request->location,
                'description' => $request->description,
                'start' => ['dateTime' => $request->start_time,
                'timeZone' => 'Asia/Manila'],
                'end' => ['dateTime' => $request->end_time,
                'timeZone' => 'Asia/Manila'],
            ]);

            $calendarId = '5f60ab0fe80f5fb52256c5858400e342e17127ac613eee2f37d19aed2bff487a@group.calendar.google.com'; 
            $service->events->insert($calendarId, $googleEvent);

            DB::commit();
            return response()->json(['success' => true, 'event' => $event]);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Failed to create event in Google Calendar: ' . $e->getMessage());
        }
        
    } 

    public function showSignInForm()
    {
        return view('admin.signIn');
    }

    public function signIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.Home');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function Home()
    {
        $events = Event::all();
        return view('admin.Home', compact('events')); 
    }


    public function createEvent()
    {
        return view('admin.createEvent');
    }
    
    public function viewApplications()
    {
        // Fetch all applicants from the database
        $applicants = MemberApplication::where('status', 'Pending')->get(); // Only get applicants whose status is pending
        // Pass applicants data to the view
        return view('admin.viewApplication', compact('applicants'));
    }

    public function messages()
    {
        return view('admin.messages');
    }

    public function gallery()
    {
        return view('admin.gallery');
    }

    public function createCertification()
    {
        return view('admin.createCertification');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.signIn')->with('success', 'Successfully logged out.');
    }
}
