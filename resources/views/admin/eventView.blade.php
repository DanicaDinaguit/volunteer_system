@extends('layouts.admin_app')

@section('title', 'Event Details')

@section('content')
<div style="margin-top: 50px;">
    <div class="container py-4">
        <div class="mb-4">
            <a href="{{ route('admin.event') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h4 class="card-title mb-0">{{ $event->title }}</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <p class="mb-0 text-muted">
                                <i class="fas fa-calendar-alt"></i> 
                                {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }} | 
                                {{ \Carbon\Carbon::parse($event->start)->format('g:i A') }} - {{ \Carbon\Carbon::parse($event->end)->format('h:i A') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <h6 class="text-primary"><strong>Details</strong></h6>
                            <p class="mb-1"><i class="fas fa-map-marker-alt"></i> {{ $event->event_location }}</p>
                            <p class="mb-1"><i class="fas fa-tags"></i> {{ $event->category }}</p>
                            <p><strong>Description:</strong> {{ Str::limit($event->description, 150) }}</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="text-muted">
                                <strong>Volunteers Needed:</strong> 
                                <span id="volunteers-needed" class="badge bg-success">
                                    {{ $event->number_of_volunteers - $event->volunteers_joined }}
                                </span> 
                                out of {{ $event->number_of_volunteers }}
                            </div>
                        </div>

                        @php
                            $now = \Carbon\Carbon::now();
                            $eventStart = \Carbon\Carbon::parse($event->event_date . ' ' . $event->start);
                            $eventEnd = \Carbon\Carbon::parse($event->event_date . ' ' . $event->end);

                            if ($now->lt($eventStart)) {
                                $status = 'Upcoming';
                            } elseif ($now->between($eventStart, $eventEnd)) {
                                $status = 'Ongoing';
                            } else {
                                $status = 'Done';
                            }
                        @endphp
                        <div class="mt-2 text-muted">
                            <small>Status: <strong>{{ $status }}</strong></small>
                        </div>

                        <!-- Participants Table -->
                        @if($participants->isNotEmpty())
                        <div class="mt-4">
                            <h6 class="text-primary"><strong>Participants</strong></h6>
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-hover table-bordered align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th> <!-- Column for numbering -->
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Course</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($participants as $index => $participant)
                                        <tr>
                                            <td>{{ $index + 1 }}</td> <!-- Display row number -->
                                            <td>{{ $participant->volunteer->first_name }} {{ $participant->volunteer->middle_name }} {{ $participant->volunteer->last_name }}</td>
                                            <td>{{ $participant->volunteer->email ?? 'N/A' }}</td>
                                            <td>{{ $participant->volunteer->application->course ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <p class="text-center text-muted mt-4">No participants have joined this event yet.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card {
        border-radius: 10px;
    }
    .card-header {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .table-sm td, .table-sm th {
        padding: 0.3rem;
    }
</style>
@endsection
