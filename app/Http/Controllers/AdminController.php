<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\MemberApplication;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;




class AdminController extends Controller
{
    public function showSignInForm()
    {
        return view('admin.signIn');
    }

    public function Home()
    {
        $events = Event::all();
        return view('admin.Home', compact('events')); // Make sure you have this view file created
    }

    public function calendar()
    {
         return view('admin.calendar');
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

    

    public function gallery()
    {
        return view('admin.gallery');
    }

    public function createCertification()
    {
        return view('admin.createCertification');
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
}
