@extends('layouts.admin_app')

@section('title', 'Volunteer Attendances')

@section('content')
<div class="container mt-4">
    <h4 class="text-center" style="color: #d98641;">Attendance Details for {{ $volunteerFullName }}</h4>

    @if($attendanceData->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th class="text-center">Total Event Attendance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceData as $index => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data['event']->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($data['event']->event_date)->format('F j, Y') }}</td>
                            @if($data['not_attended'])
                                <td class="text-danger">Not Attended</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>     
                            @else
                                <td class="text-success">Attended</td>
                                <td>
                                    {{ $data['attendances']->first()->time_in ? \Carbon\Carbon::parse($data['attendances']->first()->time_in)->format('h:i A') : '-' }}
                                </td>
                                <td>
                                    {{ $data['attendances']->first()->time_out ? \Carbon\Carbon::parse($data['attendances']->first()->time_out)->format('h:i A') : '-' }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total Attendances:</strong></td>
                        <td class="text-center"><strong>{{ $totalEventsAttended }}</strong></td> 
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-muted">No events found.</p>
    @endif
</div>
@endsection
