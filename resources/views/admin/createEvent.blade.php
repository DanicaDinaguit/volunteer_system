@extends('layouts.admin_app')

@section('title', 'Admin Create Event')

@section('content')
<br><br><br><br><br><br><br>
    <a href="#" class="back-arrow" style="margin-top: 110px;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19L5 12L12 5" />
        </svg>
    </a>
    
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif
    
    <form action="{{ route('events.store') }}" method="POST" class="form-event">
        @csrf
        <label for="ename">Event Name:</label><br>
        <input type="text" name="ename" required><br>
        <label for="etype">Event Type:</label><br>
        <select name="etype" required>
            <option value="Values Formation and Partnership">Values Formation and Partnership</option>
            <option value="Skills/Livelihood Training">Skills/Livelihood Training</option>
            <option value="Health and Environment">Health and Environment</option>
            <option value="Education and Technology">Education and Technology</option>
        </select><br>
        <label for="edesc">Description:</label>
        <textarea name="edesc" rows="5" cols="50" required></textarea>
        <div class="grid-row">
            <label for="slots">Volunteer Slots:</label>
            <label for="edate">Date:</label>
            <label for="timeStart">Time Start:</label>
            <label for="timeEnd">Time End:</label>
        </div>
        <div class="grid-row">
            <input type="number" id="slots" name="slots" required>
            <input type="date" id="edate" name="edate" required>
            <input type="time" id="timeStart" name="timeStart" required>
            <input type="time" id="timeEnd" name="timeEnd" required>
        </div>
        <label for="elocation">Location:</label><br>
        <input type="text" name="elocation" required><br>
        <label for="epartner">Partner/s:</label><br>
        <input type="text" name="epartner" required><br><br>
        <input type="submit" value="Add Event"><br><br>
    </form>
@endsection
