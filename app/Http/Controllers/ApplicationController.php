<?php

namespace App\Http\Controllers;

use App\Events\NewMembershipApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationSubmitted;
use App\Models\Admin;
use App\Models\MemberApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Http\Request;
use PDF;


class ApplicationController extends Controller
{

    public function formApplication()
    {
        // Handle the case where no user is authenticated
        $user = $this->currentUser();
        if (!$user) {
            // Check which guard should redirect the user
            if (\Auth::guard('admin')->viaRemember() || \Auth::guard('admin')->guest()) {
                // Redirect admin users
                return redirect()->route('admin.signin')->with('error', 'Your session has expired. Please log in again.');
            } elseif (\Auth::guard('web')->viaRemember() || \Auth::guard('web')->guest()) {
                // Redirect volunteer users
                return redirect()->route('volunteer.signin')->with('error', 'Your session has expired. Please log in again.');
            }
        }
        return view('admin.applicationForm');
    }

    public function download($id)
    {
        // Fetch applicant data by ID
        $applicant = MemberApplication::findOrFail($id);

        // Create a PDF from a view
        $pdf = PDF::loadView('admin.applicationForm', compact('applicant'));

        // Stream the generated PDF (you can also use ->download('filename.pdf') to force download)
        return $pdf->stream('application_' . $applicant->name . '.pdf');
    }

    public function submitApplication(Request $request)
    {
        try {
            // Validate the form data
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone_number' => [
                    'required',
                    'regex:/^\+?[0-9]{10,15}$/', // Allows international format and ensures 10-15 digits
                ],
                'email_address' => 'required|email|max:255',
                'birthdate' => 'required|date',
                'street_address' => 'nullable|string|max:255', // Made optional
                'city' => 'required|string|max:255',
                'state' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:10',
                'country' => 'required|string|max:255',
                'civil_status' => 'required|in:Single,Married,Divorced,Widowed',
                'religion' => 'required|string|max:50',
                'gender' => 'required|in:Male,Female,Other',
                'citizenship' => 'required|string|max:50',
                'college' => 'required|string|max:255',
                'course' => 'required|string|max:255',
                'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year',
                'schoolID' => 'required|string|max:50',
                'high_school' => 'nullable|string|max:255',
                'elementary' => 'nullable|string|max:255',
                'reasons_for_joining' => 'required|string',
            ], [
                'first_name.required' => 'The first name is required.',
                'last_name.required' => 'The last name is required.',
                'phone_number.required' => 'The phone number is required.',
                'phone_number.regex' => 'The phone number must be between 10 and 15 digits and may include an optional "+" prefix.',
                'email_address.required' => 'The email address is required.',
                'email_address.email' => 'Please provide a valid email address.',
                'birthdate.required' => 'The birthdate is required.',
                // Add more custom messages for other fields as needed
            ]);
    
            // Combine address fields for storage (only if street_address is provided)
            $validatedData['address'] = "{$validatedData['street_address']}, {$validatedData['city']}, {$validatedData['state']}, {$validatedData['postal_code']}, {$validatedData['country']}";
            if (is_null($validatedData['street_address'])) {
                $validatedData['address'] = "{$validatedData['city']}, {$validatedData['state']}, {$validatedData['postal_code']}, {$validatedData['country']}";
            }
    
            // Set default status to 'Pending'
            $validatedData['status'] = 'Pending';
    
            // Log the validated data
            Log::info('Validated Data:', $validatedData);
    
            // Create a new member application
            $application = MemberApplication::create($validatedData);
    
            // Log the creation of the application with status
            Log::info('Application Created:', $application->toArray());
    
            // Send email notification
            Mail::to($validatedData['email_address'])->send(new ApplicationSubmitted($validatedData));
    
            // Create a notification for all admins
            $admins = Admin::all();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->adminID,
                    'user_type' => Admin::class,
                    'type' => 'New Membership Application',
                    'title' => 'New Membership Application Submitted',
                    'body' => 'A new membership application has been submitted by ' . $validatedData['first_name'] . ' ' . $validatedData['last_name'] . '.',
                    'url' => route('admin.viewApplication', $application->id),
                    'is_read' => false,
                ]);
            }
    
            // Trigger an event for the new application
            event(new NewMembershipApplication($application));
            Log::info('Notification created for admin ID: ' . $admin->adminID);
    
            // Redirect back with a success message
            return redirect()->back()->with('success', 'Application submitted successfully!');
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('Error submitting application: ' . $e->getMessage());
            return redirect()->back()->withErrors('Failed to submit application. Please try again.');
        }
    }  

    public function getApplicantDetails($memberApplicationID)
    {
        // $applicant = MemberApplication::findOrFail($memberApplicationID);
        $applicant = DB::table('tblmemberapplication')->where('memberApplicationID', $memberApplicationID)->first();
        if (!$applicant) {
            return response()->json(['message' => 'Applicant not found'], 404);
        }
        return response()->json($applicant);
    }

    public function approveApplication($memberApplicationID)
    {
        // Approve the application logic
        $application = MemberApplication::where('memberApplicationID', $memberApplicationID)->first();

        // Log the application instance to check if it's found
        \Log::info('applicant:', [$application]);

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        // Update the status
        $application->status = 'approved';
        $application->save();

        return response()->json(['message' => 'Application approved successfully']);
    }

    public function rejectApplication($memberApplicationID)
    {
        // Approve the application logic
        $application = MemberApplication::where('memberApplicationID', $memberApplicationID)->first();

        // Log the application instance to check if it's found
        \Log::info('applicant:', [$application]);

        if (!$application) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        // Update the status
        $application->status = 'rejected';
        $application->save();

        return response()->json(['message' => 'Application rejected successfully']);
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // Get the search query
        $applications = MemberApplication::where('first_name', 'like', "%$query%")
                                    ->orWhere('middle_name', 'like', "%$query%")
                                    ->orWhere('last_name', 'like', "%$query%")
                                    ->orWhere('email_address', 'like', "%$query%")
                                    ->get();

        return response()->json($applications);
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
