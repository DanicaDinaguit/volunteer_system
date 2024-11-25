<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Participant;  // Assuming you have a Participant model
use App\Models\Event;
use App\Models\BeneficiaryAttendance;
use App\Models\MemberCredential;
use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
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
    
        // Fetch beneficiaries who attended the event
        $beneficiariesAttendance = BeneficiaryAttendance::where('eventID', $id)->get();
        
        // Fetch all partners or filter based on some criteria related to the event
        $partners = Partner::all();
    
        return view('admin/attendance', compact('event', 'attendances', 'beneficiariesAttendance', 'partners'));
    }

    public function attendanceSummary(Request $request, $returnView = true)
    {
        try {
            // Default date range (semester-based)
            $date_start = $request->get('date_start') ?? now()->startOfYear()->toDateString();
            $date_end = $request->get('date_end') ?? now()->endOfYear()->toDateString();
    
            // Validate that the date range is correct
            $request->validate([
                'date_start' => 'required|date',
                'date_end' => 'required|date',
            ]);
    
            // Filter events within the provided date range and group them by category
            $events = Event::whereBetween('event_date', [$date_start, $date_end])
                ->get()
                ->groupBy('category'); // Group events by category
            $eventAll = Event::whereBetween('event_date', [$date_start, $date_end])->get();
    
            // Get participant IDs for the events within the date range
            $participantIds = Participant::whereIn('eventID', $events->flatten()->pluck('id'))->pluck('participantsID');
    
            // Fetch attendance records for those participants
            $attendances = Attendance::with('participant.event')->whereIn('participantsID', $participantIds)->get();
    
            // Group attendances by volunteer (participant)
            $groupedAttendances = $attendances->groupBy('participantsID');
    
            // Calculate total attendance for each volunteer and certificate tier
            $attendanceData = $groupedAttendances->map(function ($attendances, $participantID) {
                $totalEventsAttended = $attendances->count();
                $eventsPresent = $attendances->where('status', ['present', 'late'])->count();
    
                // Determine certificate tier based on attendance percentage
                $attendancePercentage = ($totalEventsAttended > 0) ? ($eventsPresent / $totalEventsAttended) * 100 : 0;
                $certificateTier = 'Bronze';  // Default tier
    
                if ($attendancePercentage >= 70) {
                    $certificateTier = 'Silver';
                }
                if ($attendancePercentage >= 90) {
                    $certificateTier = 'Gold';
                }
    
                $volunteer = $attendances->first()->participant->volunteer;
                $volunteerFullName = "{$volunteer->first_name} {$volunteer->middle_name} {$volunteer->last_name}";
    
                return [
                    'volunteer' => $volunteer,
                    'volunteer_full_name' => $volunteerFullName,
                    'volunteer_id' => $volunteer->memberCredentialsID,
                    'attendance' => $attendances,
                    'total_events_attended' => $totalEventsAttended,
                    'certificate_tier' => $certificateTier,
                    'events_present' => $eventsPresent
                ];
            });
    
            // Return data for use in other functions
            $data = [
                'attendanceData' => $attendanceData,
                'events' => $events,
                'eventAll' => $eventAll,
                'date_start' => $date_start,
                'date_end' => $date_end,
            ];
    
            // Return view if requested
            if ($returnView) {
                return view('admin.attendanceSummary', $data);
            }
            return $data;
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error in attendanceSummary: ' . $e->getMessage());
    
            // Optionally, redirect back with an error message
            return redirect()->back()->with('error', 'Something went wrong while fetching attendance summary.');
        }
    }
    
          
    public function getVolunteerAttendance($volunteerId)
    {
        try {
            // Fetch all events
            $events = Event::all();
    
            // Fetch the volunteer's attendance records
            $attendances = Attendance::with('participant.event') // Eager load Participant and Event relationships
                ->whereHas('participant', function ($query) use ($volunteerId) {
                    $query->where('memberCredentialsID', $volunteerId); // Filter by volunteer ID
                })
                ->get()
                ->groupBy('participant.eventID'); // Group by eventID for easy lookup
    
            // Fetch volunteer details
            $volunteer = MemberCredential::findOrFail($volunteerId);
            $volunteerFullName = "{$volunteer->first_name} {$volunteer->middle_name} {$volunteer->last_name}";
    
            // Prepare attendance data
            $attendanceData = $events->map(function ($event) use ($attendances) {
                $eventAttendances = $attendances->get($event->id) ?? collect(); // Get attendance records for this event or an empty collection
                Log::info('Get  EventAttendance: ', [$eventAttendances]);
                
                return [
                    'event' => $event,
                    'attendances' => $eventAttendances,
                    'total_events_attended' => $eventAttendances->count(),
                    'events_present' => $eventAttendances->where('status', 'present')->count(),
                    'not_attended' => $eventAttendances->isEmpty(), // Check if the volunteer did not attend
                ];
            });
            $totalEventsAttended = $attendanceData->sum('total_events_attended');
            $eventsPresent = $attendanceData->sum('events_present');
            return view('admin.volunteerAttendance', compact('attendanceData', 'volunteerFullName', 'totalEventsAttended', 'eventsPresent'));
        } catch (\Exception $e) {
            Log::error('Error in getVolunteerAttendance: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while fetching attendance for this volunteer.'], 500);
        }
    }
       
    public function getEventAttendance($eventId)
    {
        try {
            // Fetch the attendance records for the given event ID
            $attendance = Attendance::with('participant.volunteer')
                ->whereHas('participant', function($query) use ($eventId) {
                    $query->where('eventID', $eventId);
                })
                ->get();

            // Group attendance records by volunteer
            $groupedAttendance = $attendance->groupBy('participantsID');

            // Prepare the response data
            $attendanceData = $groupedAttendance->map(function($attendances, $participantId) {
                $volunteer = $attendances->first()->participant->volunteer;
                $status = $attendances->where('status', 'present')->count();
                return [
                    'volunteer_id' => $volunteer->id,
                    'volunteer_name' => "{$volunteer->first_name} {$volunteer->last_name}",
                    'attendance_count' => $attendances->count(),
                    'present_count' => $status,
                ];
            });

            return response()->json(['attendance' => $attendanceData]);

        } catch (\Exception $e) {
            Log::error('Error in getEventAttendance: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong while fetching attendance for this event.'], 500);
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
    public function downloadStatistics(Request $request)
    {
        // Extract the date range from the request
        $dateStart = $request->input('date_start');
        $dateEnd = $request->input('date_end');
    
        if (!$dateStart || !$dateEnd) {
            return redirect()->back()->with('error', 'Date range is required to download statistics.');
        }
    
        // Retrieve events within the provided date range and group them by category
        $events = Event::whereBetween('event_date', [$dateStart, $dateEnd])
            ->get()
            ->groupBy('category'); // Group events by category
    
        $eventAll = Event::whereBetween('event_date', [$dateStart, $dateEnd])->get();
    
        // Get participant IDs for the events within the date range
        $participantIds = Participant::whereIn('eventID', $eventAll->pluck('id'))->pluck('participantsID');
    
        // Fetch attendance records for those participants
        $attendances = Attendance::with('participant.event')->whereIn('participantsID', $participantIds)->get();
    
        // Group attendances by volunteer (participant)
        $groupedAttendances = $attendances->groupBy('participantsID');
    
        // Calculate total attendance for each volunteer and determine certificate tier
        $attendanceData = $groupedAttendances->map(function ($attendances, $participantID) {
            $totalEventsAttended = $attendances->count();
            $eventsPresent = $attendances->whereIn('status', ['present', 'late'])->count();
    
            // Determine certificate tier based on attendance percentage
            $attendancePercentage = ($totalEventsAttended > 0) ? ($eventsPresent / $totalEventsAttended) * 100 : 0;
            $certificateTier = 'Bronze';
    
            if ($attendancePercentage >= 70) {
                $certificateTier = 'Silver';
            }
            if ($attendancePercentage >= 90) {
                $certificateTier = 'Gold';
            }
    
            $volunteer = $attendances->first()->participant->volunteer;
            $volunteerFullName = "{$volunteer->first_name} {$volunteer->middle_name} {$volunteer->last_name}";
    
            return [
                'volunteer' => $volunteer,
                'volunteer_full_name' => $volunteerFullName,
                'volunteer_id' => $volunteer->memberCredentialsID,
                'attendance' => $attendances,
                'total_events_attended' => $totalEventsAttended,
                'certificate_tier' => $certificateTier,
                'events_present' => $eventsPresent,
            ];
        });
    
        // Load the data into the PDF view
        $pdf = PDF::loadView('admin.attendanceStatistics', compact(
            'attendanceData',
            'events',
            'eventAll',
            'dateStart',
            'dateEnd'
        ));
    
        // Download the PDF
        return $pdf->download("attendance_summary_{$dateStart}_to_{$dateEnd}.pdf");
    }            
}

