<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;


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
    public function profile()
    {
        return view('admin.profile'); // Make sure you have this view file created
    }
    public function signIn(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.Home');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.signin')->with('success', 'Successfully logged out.');
    }
}
