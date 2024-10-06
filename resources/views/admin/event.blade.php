@extends('layouts.admin_app')

@section('title', 'Event Dashboard')

@section('content')
<div style="margin-top: 115px;">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Event Dashboard</h2>
            <!-- Trigger the Modal -->
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createEventModal">
                <i class="fas fa-plus me-2"></i>Create New Event
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Collapsible Filters -->
        <div class="mb-4">
            <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                <i class="fas fa-filter"></i> Toggle Filters
            </button>
            <div class="collapse mt-3" id="filterSection">
                <form method="GET" action="{{ route('admin.event') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="etype" class="form-label">Event Type</label>
                        <select id="etype" name="etype" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="Values Formation and Partnership">Values Formation and Partnership</option>
                            <option value="Skills/Livelihood Training">Skills/Livelihood Training</option>
                            <option value="Health and Environment">Health and Environment</option>
                            <option value="Education and Technology">Education and Technology</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="date_start" class="form-label">Date Start</label>
                        <input type="date" id="date_start" name="date_start" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label for="date_end" class="form-label">Date End</label>
                        <input type="date" id="date_end" name="date_end" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label for="volunteers" class="form-label">Volunteer Slots (Min)</label>
                        <input type="number" id="volunteers" name="volunteers" class="form-control form-control-sm" placeholder="Minimum slots">
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Event Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Volunteers</th>
                        <th scope="col">Attendance</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>{{ $event->event_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->event_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->event_end)->format('h:i A') }}</td>
                        <td>{{ $event->number_of_volunteers }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" title="View Attendance">
                                 <i class="fas fa">View Attendance</i> 
                            </button>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.eventDetails', $event->eventID) }}" class="btn btn-info btn-sm me-1" title="View Event">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.eventDetails', $event->eventID) }}" class="btn btn-warning btn-sm me-1" title="Edit Event">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event->eventID) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Event">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No events found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Create Event Form -->
                <form action="{{ route('admin.createEvent.submit') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label for="ename" class="form-label">Event Name</label>
                        <input type="text" id="ename" name="ename" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="etype" class="form-label">Event Type</label>
                        <select id="etype" name="etype" class="form-select" required>
                            <option value="Values Formation and Partnership">Values Formation and Partnership</option>
                            <option value="Skills/Livelihood Training">Skills/Livelihood Training</option>
                            <option value="Health and Environment">Health and Environment</option>
                            <option value="Education and Technology">Education and Technology</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="edesc" class="form-label">Description</label>
                        <textarea id="edesc" name="edesc" rows="5" class="form-control" required></textarea>
                    </div>

                    <div class="col-md-3">
                        <label for="slots" class="form-label">Volunteer Slots</label>
                        <input type="number" id="slots" name="slots" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label for="edate" class="form-label">Date</label>
                        <input type="date" id="edate" name="edate" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label for="timeStart" class="form-label">Time Start</label>
                        <input type="time" id="timeStart" name="timeStart" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label for="timeEnd" class="form-label">Time End</label>
                        <input type="time" id="timeEnd" name="timeEnd" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="elocation" class="form-label">Location</label>
                        <input type="text" id="elocation" name="elocation" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="epartner" class="form-label">Partner/s</label>
                        <input type="text" id="epartner" name="epartner" class="form-control" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
