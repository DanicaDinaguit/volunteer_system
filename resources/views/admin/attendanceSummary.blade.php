@extends('layouts.admin_app')

@section('title', 'Attendance Statistics')

@section('content')
<div class="container" style="margin-top: 70px;">
    <h4 class="text-center mb-3" style="color: #d98641; font-family: Oswald;">Attendance Summary ({{ request()->date_start }} - {{ request()->date_end }})</h4>

    @if($attendanceData->isNotEmpty())
        <!-- Statistics Section -->
        <div class="row mb-3 d-flex justify-content-center">
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white shadow-sm rounded-lg" id="total-volunteers-card" style="cursor: pointer; padding: 5px; transition: all 0.3s ease-in-out;">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="font-size: 1.1rem;">Total Volunteers</h5>
                        <h3 class="card-text" style="font-size: 1.8rem;">{{ $attendanceData->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white shadow-sm rounded-lg" id="total-events-card" style="cursor: pointer; padding: 5px; transition: all 0.3s ease-in-out;">
                    <div class="card-body text-center">
                        <h5 class="card-title" style="font-size: 1.1rem;">Total Events</h5>
                        <h3 class="card-text" style="font-size: 1.8rem;">{{ $events->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Volunteer</th>
                        @foreach($events as $event)
                            <th class="text-center">{{ $event->title }}</th>
                        @endforeach
                        <th class="text-center">Total</th>
                        <th class="text-center">Certificate Tier</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceData as $index => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data['volunteer_full_name'] ?? 'Volunteer information missing' }}</td>
                            @foreach($events as $event)
                                @php
                                    // Check if the volunteer attended this event
                                    $attendance = $data['attendance']->firstWhere('participant.eventID', $event->id);
                                @endphp
                                <td class="text-center">{{ $attendance ?  '1' : '0' }}</td>
                            @endforeach
                            <td class="text-center">{{ $data['total_events_attended'] }}</td>
                            <td class="text-center">{{ $data['certificate_tier'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-center text-muted">No attendance data found within the selected date range.</p>
    @endif
</div>

<!-- Modal for Volunteers -->
<div class="modal fade" id="volunteersModal" tabindex="-1" aria-labelledby="volunteersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="volunteersModalLabel">All Volunteers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="volunteers-list" class="list-unstyled">
                    @foreach($attendanceData as $data)
                        <li class="volunteer-item" data-volunteer-id="{{ $data['volunteer_id'] }}">
                            <a href="javascript:void(0)">{{ $data['volunteer_full_name'] ?? 'Volunteer information missing' }}</a>
                        </li>
                    @endforeach
                </ul>

                <!-- Volunteer attendance details will appear here -->
                <div id="volunteer-details" style="margin-top: 20px;">
                    <h5>Attendance Details:</h5>
                    <div id="attendance-details-list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Events -->
<div class="modal fade" id="eventsModal" tabindex="-1" aria-labelledby="eventsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventsModalLabel">All Events</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul id="events-list" class="list-unstyled">
                    @foreach($events as $event)
                        <li class="event-item" data-event-id="{{ $event->id }}">
                            <a href="javascript:void(0)">{{ $event->title }}</a>
                        </li>
                    @endforeach
                </ul>

                <!-- Event attendance details will appear here -->
                <div id="event-details" style="margin-top: 20px;">
                    <h5>Attendance for this Event:</h5>
                    <div id="event-attendance-details"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Handle clicks on Total Volunteers and Total Events cards
    document.getElementById('total-volunteers-card').addEventListener('click', function() {
        $('#volunteersModal').modal('show');
    });

    document.getElementById('total-events-card').addEventListener('click', function() {
        $('#eventsModal').modal('show');
    });

    // Handle clicks on individual volunteers to show their attendance details
    document.querySelectorAll('.volunteer-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const volunteerId = item.getAttribute('data-volunteer-id');

            // Fetch attendance details for this volunteer
            fetch(`/admin/getVolunteerAttendance/${volunteerId}`)
                .then(response => response.json())
                .then(data => {
                    let detailsHtml = '<ul>';
                    data.attendance.forEach(att => {
                        detailsHtml += `<li>${att.event_title}: ${att.status ? 'Attended' : 'Absent'}</li>`;
                    });
                    detailsHtml += '</ul>';
                    document.getElementById('attendance-details-list').innerHTML = detailsHtml;
                })
                .catch(error => console.log('Error fetching volunteer attendance:', error));
        });
    });

    // Handle clicks on individual events to show attendance for that event
    document.querySelectorAll('.event-item').forEach(function(item) {
        item.addEventListener('click', function() {
            const eventId = item.getAttribute('data-event-id');

            // Fetch attendance details for this event
            fetch(`/admin/getEventAttendance/${eventId}`)
                .then(response => response.json())
                .then(data => {
                    let detailsHtml = '<ul>';
                    data.attendance.forEach(att => {
                        detailsHtml += `<li>${att.volunteer_name}: ${att.status ? 'Attended' : 'Absent'}</li>`;
                    });
                    detailsHtml += '</ul>';
                    document.getElementById('event-attendance-details').innerHTML = detailsHtml;
                })
                .catch(error => console.log('Error fetching event attendance:', error));
        });
    });
</script>
@endsection
