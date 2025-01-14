<?php

namespace App\Http\Controllers;

use App\Models\MemberCredential;
use App\Models\Event;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MemberController extends Controller
{
    public function showSignUpForm()
    {
        return view('volunteer.signUp');
    }
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'string|max:255',
            'last_name' => 'required|string|max:255',
            'studentID' => 'required|string|max:255',
            'email' => 'required|email|unique:tblmembercredentials,email',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[0-9]/', // At least one digit
                'regex:/[@$!%*#?&]/', // At least one special character
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
        ]);

        // Check if the email exists in tblmemberapplication and the application is approved
        $application = DB::table('tblmemberapplication')
            ->where('email_address', $validatedData['email'])
            ->first();

        if (!$application) {
            // Email does not exist in tblmemberapplication
            return redirect()->back()->with('error', 'No application found for this email.');
        } elseif ($application->status !== 'Approved') {
            // Application exists but not approved
            return redirect()->back()->with('error', 'Your application is not approved yet.');
        }

        // If the email exists and the application is approved, proceed with sign-up
        // Create a new position entry for 'member'
        $position = Position::create([
            'position_name' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Refresh the position instance to ensure the ID is available
        $position->refresh();

        // Ensure positionID is not null
        if ($position && $position->positionID) {
            // Create a new member
            $member = new MemberCredential;
            $member->positionID = $position->positionID;
            $member->first_name = $validatedData['first_name'];
            $member->middle_name = $validatedData['middle_name'];
            $member->last_name = $validatedData['last_name'];
            $member->studentID = $validatedData['studentID'];
            $member->email = $validatedData['email'];
            $member->password = Hash::make($validatedData['password']); // Hash the password
            
            // Store the memberApplicationID from the application table
            $member->memberApplicationID = $application->memberApplicationID;
            
            $member->created_at = now(); // Explicitly set created_at
            $member->updated_at = now(); // Explicitly set updated_at
            $member->save();

            // Log member creation success
            Log::info('Member created successfully:', ['memberID' => $member->id]);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Signup successful!');
        } else {
            // Log position creation failure
            Log::error('Failed to create position or positionID is null.');

            // Redirect back with an error message
            return redirect()->back()->with('error', 'Signup failed due to position creation issue.');
        }
    }

    public function Home()
    {
        $events = Event::take(3)->get();
        return view('volunteer.Home', compact('events')); // Make sure you have this view file created
    }

    public function showSignInForm()
    {
        return view('volunteer.signIn');
    }
    
    public function calendar()
    {
        return view('volunteer.calendar');
    }

    public function messages()
    {
        return view('volunteer.messages');
    }

    public function notification()
    {
        return view('volunteer.notification');
    }
    
    public function gallery()
    {
        return view('volunteer.gallery');
    } 

    public function signIn(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $credentials = $request->only('email', 'password');
    
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate(); // Regenerate session ID on login
            Log::info('Session Data:', session()->all());
            return redirect()->route('volunteer.Home');
        }
    
        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
    
    public function profile(Request $request)
    {
        $volunteer = Auth::guard('web')->user();

        Log::info('Volunteer User:', ['volunteer' => $volunteer]);
        if (!$volunteer) {
            return redirect()->route('volunteer.signin')->withErrors(['message' => 'You must be logged in to view your profile.']);
        }
        // Check for edit mode
        $editing = $request->has('edit') && $request->query('edit') == 'true';

        return view('volunteer.profile', compact('volunteer', 'editing'));
    }

    public function updateProfile(Request $request)
    {
        $volunteer = Auth::guard('web')->user();
        Log::info('Update Profile Request Data:', $request->all());
        
        $rules = [
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'studentID' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'password' => 'nullable|string|min:8|confirmed',
            'aboutMe' => 'nullable|string',
        ];
        $validatedData = $request->validate(array_filter($rules, function ($rule, $key) use ($request) {
            return $request->has($key);
        }, ARRAY_FILTER_USE_BOTH));
    
        if ($request->filled('first_name')) {
            $volunteer->first_name = $request->input('first_name');
        }
        if ($request->filled('middle_name')) {
            $volunteer->last_name = $request->input('middle_name');
        }
        if ($request->filled('last_name')) {
            $volunteer->middle_name = $request->input('last_name');
        }
        if ($request->filled('studentID')) {
            $volunteer->studentID = $request->input('studentID');
        }
        if ($request->filled('email')) {
            $volunteer->email = $request->input('email');
        }
    
        // Update password only if a new one is provided
        if ($request->filled('password')) {
            $volunteer->password = Hash::make($request->input('password'));
        }
        
        if ($request->filled('aboutMe')) {
            $volunteer->aboutMe = $request->input('aboutMe');
        }

        $volunteer->save();

        Log::info('Volunteer Profile Updated:', ['volunteer' => $volunteer]);
        return redirect()->route('volunteer.profile')->with('success', 'Profile updated successfully!');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('volunteer.signin')->with('success', 'Successfully logged out.');
    } 

    public function notifications()
    {
        $volunteer = Auth::guard('web')->user();
        if (!$volunteer) {
            // Check which guard should redirect the user
            if (\Auth::guard('admin')->viaRemember() || \Auth::guard('admin')->guest()) {
                // Redirect admin users
                return redirect()->route('admin.signin')->with('error', 'Your session has expired. Please log in again.');
            } elseif (\Auth::guard('web')->viaRemember() || \Auth::guard('web')->guest()) {
                // Redirect volunteer users
                return redirect()->route('volunteer.signin')->with('error', 'Your session has expired. Please log in again.');
            }
        }
        $notifications = Notification::where('user_id', $volunteer->memberCredentialsID)
                                     ->where('user_type', MemberCredential::class)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return view('volunteer.notification', compact('notifications'));
    }
}