@extends('layouts.admin_app')

@section('title', 'Home - SOCI')

@section('content')
    <!-- div for Banner -->
    <div id="banner">
            <!-- <div>
                <h1 class="volunteerneeded">Volunteer<br/><br/><br/><br/><br/>Needed</h1> -->
                <!-- <hr> -->
                <!-- <p class="bannertext">Come join us in helping those who need help with </br> health and education services.</p> -->
                <!-- <img class="unite" src="{{ asset('images/uniteasone.png') }}" alt="Unite as One" > -->
            <!-- </div> -->
            <!-- <div>
                <img src="{{ asset('images/hero-banner.png') }}"  class="bannerImg" alt="Banner Image">
            </div> -->
    </div>
    <div id="hero-content">
        <img src="{{ asset('images/LOGO.png') }}">
    </div>
    <div>
        <h1 id="hero-title">LET'S MAKE THE WORLD A BETTER PLACE</h1>
    </div>

    <!-- div for events flexed -->
    <div>
        <div id="eventItems">
            @foreach($events as $event)
                <div class="event-box">
                    <div class="day-container">
                        <p>{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</p>
                    </div>
                    <img class="img-fluid" src="{{ asset('images/event-image.jpg') }}" alt="Event Image">
                    <div class="event-info">
                        <h2>{{ $event->event_name }}</h2>
                        <p>{{ $event->event_location }}</p>
                        <p>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
                        <p>{{ $event->event_time }}</p>
                    </div>
                </div>
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


