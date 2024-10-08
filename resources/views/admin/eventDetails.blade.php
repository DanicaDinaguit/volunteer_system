@extends('layouts.admin_app')

@section('title', 'Admin Event Details')

@section('content')
<div style="margin-top: 115px;">
    <div class="container mt-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('admin.event') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Events
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Event Details Form -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="mb-4">Event Details</h3>

                <!-- Update Event Form -->
                <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label for="ename" class="form-label">Event Name</label>
                        <input type="text" id="ename" name="ename" class="form-control" value="{{ $event->title }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="etype" class="form-label">Event Type</label>
                        <select id="etype" name="etype" class="form-select" required>
                            <option value="Values Formation and Partnership" {{ $event->category == 'Values Formation and Partnership' ? 'selected' : '' }}>Values Formation and Partnership</option>
                            <option value="Skills/Livelihood Training" {{ $event->category == 'Skills/Livelihood Training' ? 'selected' : '' }}>Skills/Livelihood Training</option>
                            <option value="Health and Environment" {{ $event->category == 'Health and Environment' ? 'selected' : '' }}>Health and Environment</option>
                            <option value="Education and Technology" {{ $event->category == 'Education and Technology' ? 'selected' : '' }}>Education and Technology</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label for="edesc" class="form-label">Description</label>
                        <textarea id="edesc" name="edesc" rows="5" class="form-control" required>{{ $event->description }}</textarea>
                    </div>

                    <div class="col-md-3">
                        <label for="slots" class="form-label">Volunteer Slots</label>
                        <input type="number" id="slots" name="slots" class="form-control" value="{{ $event->number_of_volunteers }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="edate" class="form-label">Date</label>
                        <input type="date" id="edate" name="edate" class="form-control" value="{{ $event->event_date }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="timeStart" class="form-label">Time Start</label>
                        <input type="time" id="timeStart" name="timeStart" class="form-control" value="{{ $event->start }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="timeEnd" class="form-label">Time End</label>
                        <input type="time" id="timeEnd" name="timeEnd" class="form-control" value="{{ $event->end }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="elocation" class="form-label">Location</label>
                        <input type="text" id="elocation" name="elocation" class="form-control" value="{{ $event->event_location }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="epartner" class="form-label">Partner/s</label>
                        <input type="text" id="epartner" name="epartner" class="form-control" value="{{ $event->partners }}" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                </form>

                <!-- Delete Event Form -->
                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="mt-3" onsubmit="return confirm('Are you sure you want to delete this event?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Delete Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
