@extends('layouts.admin_app')

@section('title', 'Attendance Statistics')

@section('content')
<div class="container py-4">
    <h4 class="text-center mb-4">Attendance Summary ({{ request()->date_start }} - {{ request()->date_end }})</h4>

    @if($events)
        <!-- Statistics Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h5>Total Volunteers</h5>
                        <h2>{{ $attendances->unique('participantsID')->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5>Total Events</h5>
                        <h2>{{ $events->count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h5>Late</h5>
                        <h2>{{ $attendances->where('status', 'late')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>Volunteer</th>
                    <th>Event Title</th>
                    <th>Event Date</th>
                    <th>Status</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Total Events Attended</th>
                </tr>
            </thead>
            <tbody>
                <!-- Group attendances by volunteer -->
                @foreach($attendances->groupBy('participantsID') as $participantID => $participantAttendances)
                    @php
                        $firstAttendance = $participantAttendances->first();
                        $firstEvent = $firstAttendance->event ?? null;
                        $participant = $firstAttendance->participant;
                        $volunteer = $participant->volunteer ?? null;
                        $totalEventsAttended = $participantAttendances->count();
                    @endphp

                    <tr>
                        <!-- Display the volunteer's full name -->
                        <td>{{ $firstAttendance->full_name ?? 'Volunteer information missing' }}</td>

                        <!-- Check if the event exists before trying to access its properties -->
                        @if($firstEvent)
                            <td>{{ $firstEvent->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($firstEvent->event_date)->format('F j, Y') }}</td>
                        @else
                            <td colspan="2">Event information missing</td>
                        @endif
                        
                        <td>{{ ucfirst($firstAttendance->status) }}</td>
                        <td>{{ $firstAttendance->time_in ?? 'N/A' }}</td>
                        <td>{{ $firstAttendance->time_out ?? 'N/A' }}</td>
                        <td rowspan="{{ $totalEventsAttended }}">{{ $totalEventsAttended }}</td>
                    </tr>

                    <!-- Display remaining event details for the volunteer -->
                    @foreach($participantAttendances->slice(1) as $attendance)
                        @php
                            $event = $attendance->event ?? null;
                        @endphp
                        <tr>
                            <td>{{ $volunteer ? $volunteer->full_name : 'Volunteer information missing' }}</td>
                            @if($event)
                                <td>{{ $event->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</td>
                            @else
                                <td colspan="2">Event information missing</td>
                            @endif
                            <td>{{ ucfirst($attendance->status) }}</td>
                            <td>{{ $attendance->time_in ?? 'N/A' }}</td>
                            <td>{{ $attendance->time_out ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-center">No events found within the selected date range.</p>
    @endif
</div>
@endsection
