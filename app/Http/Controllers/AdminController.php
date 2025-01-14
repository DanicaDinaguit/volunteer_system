<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\MemberApplication;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\Partner;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Google_Client;  
use Google_Service_Calendar;  
use Google_Service_Calendar_Event;

class AdminController extends Controller
{
    public function calendar()
    {
        $user = $this->currentUser();
        
        $googleCalendarController = new GoogleCalendarController();
        $response = $googleCalendarController->fetchEvents();
        $events = json_decode($response->getContent(), true);
        \Log::info('Event:', ['events' => $events]);
        $partners = Partner::all();
        // Handle the case where there is user is authenticated either admin or volunteer
        if ($user) {
            // Redirect based on whether the user is an admin or volunteer
            if (\Auth::guard('admin')->check()) {
                return view('admin.calendar', compact('events', 'partners'));
            } elseif (\Auth::guard('web')->check()) {
                return view('volunteer.calendar', compact('events', 'partners'));
            } else {
                return view('calendar', compact('events', 'partners'));
            }
        } else {
            // Redirect based on whether the user is an admin or volunteer
            if (\Auth::guard('admin')->check()) {
                return redirect()->route('admin.signin')->with('error', 'You need to be logged in to access this page.');
            } elseif (\Auth::guard('web')->check()) {
                return redirect()->route('volunteer.signin')->with('error', 'You need to be logged in to access this page.');
            } else {
                return view('calendar', compact('events'));
            }
        }
    }

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
            if ($startDateTime >= $endDateTime) {
                throw new \Exception('Start time must be earlier than end time.');
            }
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
    public function showSignInForm()
    {
        return view('admin.signin');
    }

    public function Home()
    {
        $events = Event::take(3)->get();
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

    //Update admin profile
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

    // Admin logout function
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.signin')->with('success', 'Successfully logged out.');
    }

    // New Method for Notifications
    public function notifications()
    {   
        $admin = Auth::guard('admin')->user();
        // Handle the case where no user is authenticated
        if (!$admin) {
            return redirect()->route('admin.signin')->with('error', 'You need to be logged in to access this page.');
        }
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
        Log::info('Deleting', $notification);
        if ($notification) {
            // Delete the notification if found
            $notification->delete();
            return response()->json(['success' => true]);
        }

        // Return a 404 error if the notification is not found
        return response()->json(['success' => false], 404);
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
