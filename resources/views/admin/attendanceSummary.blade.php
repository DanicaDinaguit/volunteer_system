@extends('layouts.admin_app')

@section('title', 'Attendance Statistics')

@section('content')
<div class="container" style="margin-top: 40px;">
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
                        <h3 class="card-text" style="font-size: 1.8rem;">{{ $eventAll->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('admin.attendanceStatistics', ['date_start' => request()->date_start, 'date_end' => request()->date_end]) }}" 
            class="btn btn-info btn-sm text-white">
            <i class="fas fa-download"></i> Export
        </a>

        <!-- Attendance Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Volunteer</th>
                        <th colspan="{{ count($events) }}" class="text-center">Event Type</th> <!-- Add a heading for event types -->
                        <th class="text-center">Total</th>
                        <th class="text-center">Certificate</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        @foreach($events as $category => $categoryEvents)
                            <th class="text-center">{{ $category }}</th> <!-- Display category names under "Event Type" -->
                        @endforeach
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceData as $index => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data['volunteer_full_name'] ?? 'Volunteer information missing' }}</td>
                            @foreach($events as $category => $categoryEvents)
                                @php
                                    Log::info('Attendance Data', [
                                        'attendance' => $data['attendance']->toArray()
                                    ]);

                                    // Calculate attendance for this volunteer in this category
                                    $eventsInCategory = $categoryEvents->pluck('id')->toArray();  // Get event IDs as an array

                                    // Filter attendance based on events in the current category
                                    $attendanceInCategory = $data['attendance']->filter(function ($attendance) use ($eventsInCategory) {
                                        // Access the event ID through the nested participant relationship
                                        $eventId = $attendance->participant->event->id;

                                        // Check if the event ID is in the list of event IDs for the current category
                                        return in_array($eventId, $eventsInCategory);
                                    });

                                    // Log information about the category, event IDs, and attendance count
                                    Log::info('Processing category attendance', [
                                        'category' => $category,
                                        'events_in_category' => $eventsInCategory,
                                        'attendance_in_category_count' => $attendanceInCategory->count(),  // Log the count of filtered attendance
                                    ]);
                                @endphp
                                <td class="text-center">{{ $attendanceInCategory->count() }}</td>  
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
                    <li>
                        <a href="{{ route('admin.volunteerAttendance', $data['volunteer_id']) }}">
                            {{ $data['volunteer_full_name'] ?? 'Volunteer information missing' }}
                        </a>
                    </li>
                    @endforeach
                </ul>
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
                    @foreach($eventAll as $event)
                        <li class="event-item" data-event-id="{{ $event->id }}">
                            <!-- Link to the admin.attendance.show route -->
                            <a href="{{ route('admin.attendance.show', ['id' => $event->id]) }}">{{ $event->title }}</a>
                        </li>
                    @endforeach
                </ul>
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
