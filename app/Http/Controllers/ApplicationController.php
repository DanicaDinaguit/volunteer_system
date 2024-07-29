<?php

namespace App\Http\Controllers;

use App\Models\MemberApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationSubmitted;
use Illuminate\Support\Facades\Log; // Add this line to use logging

class ApplicationController extends Controller
{
    public function submitApplication(Request $request)
    {
        try {
            // Validate the form data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'email_address' => 'required|email|max:255',
                'age' => 'required|integer',
                'address' => 'required|string|max:255',
                'religion' => 'required|string|max:50',
                'gender' => 'required|in:Male,Female,Other',
                'citizenship' => 'required|string|max:50',
                'civil_status' => 'required|in:Single,Married,Divorced,Widowed',
                'college' => 'required|string|max:255',
                'course' => 'required|string|max:255',
                'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,5th Year',
                'schoolID' => 'required|string|max:50',
                'high_school' => 'required|string|max:255',
                'elementary' => 'required|string|max:255',
                'reasons_for_joining' => 'required|string',
            ]);

            // Log the validated data
            Log::info('Validated Data:', $validatedData);

            // Create a new member application
            $application = MemberApplication::create($validatedData);

            // Send email notification
            Mail::to($validatedData['email_address'])->send(new ApplicationSubmitted($validatedData));

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Application submitted successfully!');
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('Error submitting application: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to submit application. Please try again.');
        }
    }
}
