<?php

namespace App\Http\Controllers;

use App\Models\MemberCredential;
use App\Models\Event;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'studentID' => 'required|string|max:255',
            'email' => 'required|email|unique:tblmembercredentials,email',
            'password' => 'required|string|min:8',
        ]);

        // Create a new position entry for 'member'
        $position = Position::create([
            'position_name' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Refresh the position instance to ensure the ID is available
        $position->refresh();

        // Log the position to check if it's created successfully
        Log::info('Position created:', ['positionID' => $position->positionID]);

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

    public function showSignInForm()
    {
        return view('volunteer.signIn');
    }

    public function Home()
    {
        $events = Event::all();
        return view('volunteer.Home', compact('events')); // Make sure you have this view file created
    }
    
    public function signIn(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);
    
        if (Auth::guard('web')->attempt($validatedData)) {
            return redirect()->route('volunteer.Home');
        }
    
        return redirect()->back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }
    
    public function profile()
    {
        $user = Auth::user();
        return view('volunteer.profile', [
            'user' => $user,
            'editing' => false // Initially not in editing mode
        ]);
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('volunteer.profile', [
            'user' => $user,
            'editing' => true // profile editing mode
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate the input data
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'middleName' => 'required|string|max:255',
            'schoolID' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|string|min:8|confirmed',
            'aboutMe' => 'nullable|string',
        ]);

        // Update user data
        $user->first_name = $request->input('firstName');
        $user->last_name = $request->input('lastName');
        $user->middle_name = $request->input('middleName');
        $user->studentID = $request->input('schoolID');
        $user->email = $request->input('email');
        
        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        
        $user->aboutMe = $request->input('aboutMe');
        $user->save();

        return redirect()->route('volunteer.profile')->with('success', 'Profile updated successfully!');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('volunteer.signIn')->with('success', 'Successfully logged out.');
    } 
}