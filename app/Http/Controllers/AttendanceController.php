<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Participant;  // Assuming you have a Participant model
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use PDF;

class AttendanceController extends Controller
{
    public function scan(Request $request) 
    {
        // Extract QR code data
        $qrData = json_decode($request->input('qr_data'), true); // Decoding JSON string to array
        \Log::info('QR Data:', [$qrData]);
    
        // Check if QR data contains the necessary fields
        $participantID = $qrData['Participants ID'] ?? null;
        $fullName = $qrData['Full Name'] ?? null;
        $studentID = $qrData['Student ID'] ?? null;
        $course = $qrData['Course'] ?? null;
        $eventID = $qrData['Event'] ?? null;
    
        if ($participantID && $studentID) {
            // Check if an existing attendance record exists for check-out
            $attendance = DB::table('tblattendance')
                ->join('tblparticipants', 'tblattendance.participantsID', '=', 'tblparticipants.participantsID')
                ->where('tblattendance.participantsID', $participantID)
                ->where('tblparticipants.eventID', $eventID)
                ->whereNull('tblattendance.time_out')
                ->first();
    
            if ($attendance) {
                // Participant is checking out
                DB::table('tblattendance')
                    ->where('id', $attendance->id) // Assuming there's an `attendanceID`
                    ->update([
                        'time_out' => Carbon::now(),
                        'status' => 'Present', // You can modify to check if the check-out time is late
                    ]);
    
                return response()->json([
                    'success' => true,
                    'message' => 'Time out recorded for ' . $fullName,
                    'qrData' => $qrData,
                ]);
            } else {
                // Check if the participant has already checked in
                $existingAttendance = DB::table('tblattendance')
                    ->join('tblparticipants', 'tblattendance.participantsID', '=', 'tblparticipants.participantsID')
                    ->where('tblattendance.participantsID', $participantID)
                    ->where('tblparticipants.eventID', $eventID)
                    ->whereNotNull('tblattendance.time_in') // Ensures already checked in
                    ->exists();
    
                if ($existingAttendance) {
                    return response()->json([
                        'success' => false,
                        'message' => $fullName . ' has already checked in for this event.',
                    ], 400);
                }
    
                // Participant is checking in
                $isLate = $this->isParticipantLate($eventID); // Determine if late based on your logic
    
                Attendance::create([
                    'participantsID' => $participantID,
                    'studentID'      => $studentID,
                    'eventID'        => $eventID,
                    'full_name'      => $fullName,
                    'course'         => $course,
                    'time_in'        => Carbon::now(),
                    'status'         => $isLate ? 'Late' : 'Present',
                ]);
    
                return response()->json([
                    'success' => true,
                    'message' => 'Time in recorded for ' . $fullName,
                    'qrData' => $qrData,
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR data',
            ], 400);
        }
    }
    
    /**
     * Determine if the participant is late based on event start time
     *
     * @param int $eventID
     * @return bool
     */
    protected function isParticipantLate($eventID)
    {
        // Fetch event details
        $event = Event::find($eventID);
        if (!$event) {
            return false; // Handle as needed, could throw an error
        }
    
        // Define the cutoff time for being on time
        $cutoffTime = Carbon::parse($event->start)->addMinutes(15); // e.g., 15 minutes grace period
    
        // Compare current time with cutoff time
        return Carbon::now()->greaterThan($cutoffTime);
    }
    

    public function show($id)
    {
        // Find the event
        $event = Event::findOrFail($id);
        
        // Get participants associated with the event
        $participantIds = Participant::where('eventID', $id)->pluck('participantsID');
        
        // Fetch attendance records for those participants
        $attendances = Attendance::whereIn('participantsID', $participantIds)->get();

        return view('admin/attendance', compact('event', 'attendances'));
    }

    public function attendanceSummary(Request $request)
    {
        try {
            // Default date range (semester-based)
            $date_start = $request->get('date_start') ?? now()->startOfYear()->toDateString();
            $date_end = $request->get('date_end') ?? now()->endOfYear()->toDateString();
    
            // Log the initial date range
            Log::info('Attendance Summary Date Range', ['date_start' => $date_start, 'date_end' => $date_end]);
    
            // Validate that the date range is correct
            $request->validate([
                'date_start' => 'required|date',
                'date_end' => 'required|date',
            ]);
    
            // Log after validation
            Log::info('Validation passed for date range');
    
            // Filter events within the provided date range
            $events = Event::whereBetween('event_date', [$date_start, $date_end])->get();
            
            // Log the number of events retrieved
            Log::info('Events Retrieved', ['events_count' => $events->count()]);
    
            // Get the participants for those events
            $participantIds = Participant::whereIn('eventID', $events->pluck('id'))->pluck('participantsID');
    
            // Log participant IDs
            Log::info('Participant IDs Retrieved', ['participant_ids' => $participantIds]);
    
            // Fetch attendance records for those participants
            $attendances = Attendance::with('participant.event')->whereIn('participantsID', $participantIds)->get();

                // Calculate total events attended per volunteer
            $totalEventsAttended = $attendances->groupBy('participantsID')->map->count();
            // Log the attendance count
            Log::info('Attendance Records Retrieved', ['attendance_count' => $attendances->count()]);
            Log::info('Attendance Records', ['attendances' => $attendances->toArray()]);

            // Return view with data
            return view('admin.attendanceSummary', compact('events', 'attendances', 'date_start', 'date_end', 'totalEventsAttended'));
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error in attendanceSummary: ' . $e->getMessage());
            
            // Optionally, redirect back with an error message
            return redirect()->back()->with('error', 'Something went wrong while fetching attendance summary.');
        }
    }  
    
    public function download($id)
    {
        // Retrieve the event details by event ID
        $event = Event::findOrFail($id);

        // Get all attendance records for this event
        $attendances = DB::table('tblattendance')
                        ->join('tblparticipants', 'tblattendance.participantsID', '=', 'tblparticipants.participantsID')
                        ->where('tblparticipants.eventID', $id)
                        ->select(
                            'tblattendance.*',
                            'tblparticipants.memberCredentialsID',
                            'tblparticipants.eventID'
                        )
                        ->get();

        // Create a PDF from the view, passing attendances and event details
        $pdf = PDF::loadView('admin.attendanceForm', compact('attendances', 'event'));

        // Stream the generated PDF or use ->download('filename.pdf') to force download
        return $pdf->stream('attendance_' . $event->title . '.pdf');
    }
}

