<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\MemberApplication;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
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
        return view('admin.signin');
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
        // Fetch applicants based on status
        $pendingApplicants = MemberApplication::where('status', 'Pending')->get();
        $approvedApplicants = MemberApplication::where('status', 'Approved')->get();
        $rejectedApplicants = MemberApplication::where('status', 'Rejected')->get();

        // Pass applicants data to the view
        return view('admin.viewApplication', compact('pendingApplicants', 'approvedApplicants', 'rejectedApplicants'));
    }

    public function gallery()
    {
        return view('admin.gallery');
    }

    public function profile(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        Log::info('Admin User:', ['admin' => $admin]);
        if (!$admin) {
            return redirect()->route('admin.signin')->withErrors(['message' => 'You must be logged in to view your profile.']);
        }
        // Check for edit mode
        $editing = $request->has('edit') && $request->query('edit') == 'true';

        return view('admin.profile', compact('admin', 'editing'));
    }

    public function signIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate(); // Regenerate session ID on login
            Log::info('Session Data:', session()->all());
            return redirect()->route('admin.Home');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function updateProfile(Request $request)
    {
        // Fetch the currently authenticated admin
        $admin = Auth::guard('admin')->user();

        // Log the request data for debugging
        Log::info('Update Profile Request Data:', $request->all());

        $rules = [
            'firstName' => 'nullable|string|max:255',
            'lastName' => 'nullable|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $validatedData = $request->validate(array_filter($rules, function ($rule, $key) use ($request) {
            return $request->has($key);
        }, ARRAY_FILTER_USE_BOTH));

        if ($request->filled('firstName')) {
            $admin->first_name = $request->input('firstName');
        }
        if ($request->filled('lastName')) {
            $admin->last_name = $request->input('lastName');
        }
        if ($request->filled('middleName')) {
            $admin->middle_name = $request->input('middleName');
        }
        if ($request->filled('phoneNumber')) {
            $admin->phone_number = $request->input('phoneNumber');
        }
        if ($request->filled('email')) {
            $admin->email = $request->input('email');
        }

        // Update password only if a new one is provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();

        Log::info('Admin Profile Updated:', ['admin' => $admin]);

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully!');
    }


    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.signin')->with('success', 'Successfully logged out.');
    }

    // New Method for Notifications
    public function notifications()
    {
        $admin = Auth::guard('admin')->user();
        $notifications = Notification::where('user_id', $admin->adminID)
                                     ->where('user_type', Admin::class)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('admin.notification', compact('notifications'));
    }

    public function destroy($id)
    {
        // Get the currently authenticated admin user
        $admin = Auth::guard('admin')->user();

        // Find the notification by ID, user ID, and user type
        $notification = Notification::where('id', $id)
                                    ->where('user_id', $admin->adminID)
                                    ->where('user_type', Admin::class)
                                    ->first();

        if ($notification) {
            // Delete the notification if found
            $notification->delete();
            return response()->json(['success' => true]);
        }

        // Return a 404 error if the notification is not found
        return response()->json(['success' => false], 404);
    }
}
