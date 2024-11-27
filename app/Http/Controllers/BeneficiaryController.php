<?php

// In app/Http/Controllers/BeneficiaryController.php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\BeneficiaryAttendance;
use App\Models\Event;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    // Show the beneficiary registration form
    public function create()
    {
        $beneficiaries = Beneficiary::all();
        $partners = Partner::all();
        return view('admin.createBeneficiary', compact('beneficiaries', 'partners'));
    }

    // Handle the form submission
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'purok' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
        ]);

        // Create the new beneficiary record
        Beneficiary::create($request->all());

        // Redirect back with a success message
        return redirect()->route('admin.beneficiaries')->with('success', 'Beneficiary registered successfully.');
    }

    public function addBeneficiaryAttendance(Request $request, $id)
    {
        $validatedData = $request->validate([
            'beneficiaryID' => 'required|exists:tblbeneficiary,id',
        ]);
    
        $event = Event::findOrFail($id);
    
        BeneficiaryAttendance::create([
            'eventID' => $event->id,
            'beneficiaryID' => $validatedData['beneficiaryID'],
            'date_attended' => $event->event_date,
        ]);
    
        return redirect()->route('admin.attendance.show', $event->id)
                        ->with('success', 'Beneficiary attendance added successfully.');
    }
    
    public function searchBeneficiary(Request $request)
    {
        $query = $request->input('query');

        $beneficiaries = Beneficiary::where('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->orWhere('purok', 'like', "%$query%")
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'purok']);

        return response()->json($beneficiaries);
    }
    public function download($id)
    {
        // Retrieve the event details by event ID
        $event = Event::findOrFail($id);

        // Get all attendance records for this event
        $attendances = DB::table('tblbeneficiaries_attendance')
                        ->join('tblbeneficiary', 'tblbeneficiaries_attendance.beneficiaryID', '=', 'tblbeneficiary.id')
                        ->where('tblbeneficiaries_attendance.eventID', $id)
                        ->select(
                            'tblbeneficiaries_attendance.*',
                            'tblbeneficiary.first_name',
                            'tblbeneficiary.middle_name',
                            'tblbeneficiary.last_name',
                            'tblbeneficiary.purok'
                        )
                        ->get();

        // Create a PDF from the view, passing attendances and event details
        $pdf = PDF::loadView('admin.beneficiaryAttendanceForm', compact('attendances', 'event'));

        // Stream the generated PDF or use ->download('filename.pdf') to force download
        return $pdf->stream('beneficiary_attendance_' . $event->title . '.pdf');
    }

    public function downloadListBeneficiary()
    {
        $beneficiaries = Beneficiary::all();

        $pdf = PDF::loadView('admin.beneficiaryList', compact('beneficiaries'));

        return $pdf->download('beneficiaries_list.pdf');
    }
}

