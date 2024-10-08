@extends('layouts.public_app')

@section('title', 'Public Home')

@section('content')
    <!-- Banner Section -->
    <div id="banner" class="container-fluid p-5"> <!-- Used p-5 for padding -->
        <div class="row align-items-center"> <!-- Ensured alignment of content in row -->
            <div class="col-lg-6 col-md-12 text-center text-lg-start"> <!-- Text center on small devices, start on large -->
                <h1 class="volunteerneeded">Volunteer Needed</h1> <!-- Removed excessive line breaks -->
                <hr>
                <p class="bannertext">Come join us in helping those who need help with health and education services.</p>
                <img class="unite img-fluid" src="{{ asset('images/uniteasone.png') }}" alt="Unite as One"> <!-- Added img-fluid -->
            </div>
            <div class="col-lg-6 col-md-12 text-center"> <!-- Centered image on small devices -->
                <img src="{{ asset('images/bannerImg.png') }}" class="bannerImg img-fluid" alt="Banner Image"> <!-- Added img-fluid -->
            </div>
        </div>
    </div>

    <!-- Events Section -->
    <!-- <div class="container my-5">
        <div id="eventItems" class="row">
            @foreach($events as $event)
                <div class="event-box col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="day-container">
                                <p>{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</p>
                            </div>
                            <img src="{{ asset('images/event-image.jpg') }}" class="img-fluid" alt="Event Image"> 
                            <div class="event-info mt-3">
                                <h2>{{ $event->event_name }}</h2>
                                <p>{{ $event->event_location }}</p>
                                <p>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
                                <p>{{ $event->event_time }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <button id="event-button" class="btn btn-primary">View More Events</button> 
        </div>
    </div> -->

    <!-- Mission and Vision Section -->
    <div id="MV" class="container-fluid bg-light py-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3"> <!-- Used Bootstrap card for better styling -->
                    <h2>Our Mission</h2>
                    <p>To be an agent of community transformation by fostering relevant programs through volunteerism and strong community engagement.</p>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3"> <!-- Used Bootstrap card for better styling -->
                    <h2>Our Vision</h2>
                    <p>To provide sustainable programs in order to respond to the present and future needs of the community.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Other Programs Section -->
    <div class="bg-home container my-5 py-5">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4">
                <h2>Other Programs</h2>
                <ul>
                    <li>Values Formation and Partnership</li>
                    <li>Skills/Livelihood Training</li>
                    <li>Health and Environment</li>
                    <li>Education and Technology</li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-12 mb-4 text-center">
                <h4>Become a member</h4>
                <p>Join our community today and make a difference</p>
                <p>Apply and start your journey as a valued volunteer!</p>
                <button class="btn btn-success" onclick="window.location='{{ route('application') }}'">Application Form</button> <!-- Bootstrap button class -->
            </div>
        </div>
    </div>
@endsection
