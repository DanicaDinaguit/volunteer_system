@extends('layouts.admin_app')

@section('title', 'Admin Event Details')

@section('content')
<div style="margin-top: 70px;">
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
                            <option value="">Select Event Type</option>
                            <option value="Values and Education" {{ $event->category == 'Values and Education' ? 'selected' : '' }}>Values and Education</option>
                            <option value="Partnership and Development" {{ $event->category == 'Partnership and Development' ? 'selected' : '' }}>Partnership and Development</option>
                            <option value="Environment and Health" {{ $event->category == 'Environment and Health' ? 'selected' : '' }}>Environment and Health</option>
                            <option value="Social Awareness and Concern" {{ $event->category == 'Social Awareness and Concern' ? 'selected' : '' }}>Social Awareness and Concern</option>
                            <option value="Skills and Livelihood" {{ $event->category == 'Skills and Livelihood' ? 'selected' : '' }}>Skills and Livelihood</option>
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
                        <select id="epartner" name="epartner" class="form-select" required>
                            <option value="">Select a Partner</option>
                            @foreach($partners as $id => $name)
                                <option value="{{ $name }}" {{ $event->partner == $name ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
