@extends('layouts.volunteer_app')

@section('title', 'Volunteer Home')
    
@section('content')
    <!-- div for Banner -->
    <div id="banner">
        <div id="hero-content">
            <img src="{{ asset('images/LOGO1.png') }}">
        </div>
        <div>
            <h1 id="hero-title">LET'S MAKE THE WORLD A BETTER PLACE</h1>
        </div>
    </div>

    <!-- div for events flexed -->
    <div>
        <div id="eventItems">
            @foreach($events as $event)
                <a href="{{ route('volunteer.eventDetails', $event->id) }}" class="event-link" style="text-decoration: none; color: inherit;">
                    <div class="event-box">
                        <div class="day-container">
                            <p>{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</p>
                        </div>
                        <img src="{{ asset('images/event-image.jpg') }}" alt="Event Image">
                        <div class="event-info">
                            <h2>{{ $event->title }}</h2>
                            <p>{{ $event->event_location }}</p>
                            <p>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
                            <p>{{ $event->start }} - {{ $event->end }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <button id="event-button">View More Events</button>
    </div>

    <!-- Mission and Vision -->
    <div id="MV">
        <div class="mv-container">
            <div class="mv-box mission">
                <h2>Our Mission</h2>
                <p>To be an agent of community transformation by fostering relevant programs through volunteerism and strong community engagement.</p>
            </div>
            <div class="mv-box vision">
                <h2>Our Vision</h2>
                <p>To provide sustainable programs in order to respond to the present and future needs of the community.</p>
            </div>
        </div>
    </div>
@endsection
