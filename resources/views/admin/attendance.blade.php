@extends('layouts.admin_app') <!-- Assuming you have a layout for admin -->

@section('title', 'Event Attendance')

@section('content')
<div class="container mt-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="fw-bold" style="color: #866e5d;">{{ $event->title }} - Attendance</h2>
        <a href="{{ route('admin.event', $event->id) }}" class="btn btn-outline-secondary btn-sm" style="border-color: #6f833f; color: #6f833f;">
            <i class="fas fa-arrow-left me-1"></i> Back to Event
        </a>
    </div>

    <!-- Event Details -->
    <div class="card border-0 shadow-sm mb-2" style="background-color: #f8f8f8;">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center" style="color: #6f833f;">
            <div class="event-detail">
                <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}
            </div>
            <div class="event-detail">
                <strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end)->format('h:i A') }}
            </div>
            <div class="event-detail">
                <strong>Volunteers Required:</strong> {{ $event->number_of_volunteers }}
            </div>
            <div class="event-detail">
                <strong>Total Attendance:</strong> {{ $attendances->count() }}
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h4 class="fw-bold mb-4" style="color: #866e5d;">Attendance List                            
                <a href="{{route('admin.attendanceForm', $event->id)}}" class="btn btn-info btn-sm me-1">
                    <i class="fas fa-download"></i>
                </a></h4>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-light" style="background-color: #d98641; color: #fff;">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Course</th>
                            <th scope="col">Status</th>
                            <th scope="col">Time In</th>
                            <th scope="col">Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $attendance->full_name }}</td>
                            <td>{{ $attendance->course }}</td>
                            <td>
                                <span class="badge {{ $attendance->status == 'Present' ? 'bg-success' : 'bg-warning' }}" style="background-color: {{ $attendance->status == 'Present' ? '#6f833f' : '#d98641' }};">
                                    {{ $attendance->status }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}</td>
                            <td>
                                @if($attendance->time_out)
                                    {{ \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') }}
                                @else
                                    <span class="text-muted">Not yet checked out</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No attendees yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
