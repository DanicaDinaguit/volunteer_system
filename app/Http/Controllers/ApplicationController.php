<?php

namespace App\Http\Controllers;

use App\Events\NewMembershipApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationSubmitted;
use App\Models\Admin;
use App\Models\MemberApplication;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Illuminate\Http\Request;
use PDF;


class ApplicationController extends Controller
{

    public function formApplication()
    {
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

            // Set default status to 'Pending'
            $validatedData['status'] = 'Pending';

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
                    'user_id' => $admin->adminID, // Assuming 'adminID' is the primary key in tbladmin
                    'user_type' => Admin::class, // Use the Admin model class as the user type
                    'type' => 'New Membership Application',
                    'title' => 'New Membership Application Submitted',
                    'body' => 'A new membership application has been submitted by ' . $validatedData['name'] . '.',
                    'url' => route('admin.viewApplication', $application->id), // Assuming you have a route to view the application
                    'is_read' => false,
                ]);
            }

            event(new NewMembershipApplication($application));

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
}
