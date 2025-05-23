@extends('layouts.volunteer_app')

@section('title', 'Volunteer Home')
    
@section('content')
    <!-- CAROUSEL -->
    <div>
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>

            <!-- Carousel Inner (slides) -->
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <img src="{{ asset('images/carousel-images/carousel-img1.svg') }}" class="carousel-img d-block w-100" alt="First Slide">
                    <div class="carousel-caption d-flex flex-column align-items-center mt-5 mt-md-0">
                        <img src="{{ asset('images/LOGO1.png') }}" alt="Logo" class="carousel-logo">
                        <h1 class="carousel-title">FOSTERING SUSTAINABLE COMMUNITY GROWTH AND DEVELOPMENT</h1>
                    </div> 
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel-images/carousel-img2.png') }}" class="carousel-img d-block w-100" alt="Second Slide">
                    <div class="carousel-caption d-flex flex-column align-items-center mt-5 mt-md-0">
                        <img src="{{ asset('images/LOGO1.png') }}" alt="Logo" class="carousel-logo">
                        <h1 class="carousel-title">JOIN US IN OUR MISSION</h1>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel-images/carousel-img3.png') }}" class="carousel-img d-block w-100" alt="Third Slide">
                    <div class="carousel-caption d-flex flex-column align-items-center mt-5 mt-md-0">
                        <img src="{{ asset('images/LOGO1.png') }}" alt="Logo" class="carousel-logo">
                        <h1 class="carousel-title">TOGETHER WE CAN MAKE A CHANGE</h1>
                    </div>
                </div>
            </div>

            <!-- Controls (next and previous buttons) -->
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- READ MORE SECTION -->
    <div>
        <div class="read-more text-white py-5" 
        style="
        background: url('{{ asset('images/sample-event-images/sample-event1.svg') }}') no-repeat center center/cover;
        height: 70vh auto;
        position: relative;">
            <div class="container">
                <h2 class="text-center mb-4 fw-semibold fs-2">SOCIAL ORIENTATION AND COMMUNITY INVOLVEMENT</h2>
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 col-lg-6 mt-4 px-5 px-lg-5 text-center">
                        <img src="{{ asset('images/read-more/read-more-img.png') }}" alt="Read More Image" class="img-fluid rounded-4 animate-on-scroll zoom-in">
                    </div>
                    <div class="col-12 col-lg-6 mt-4 px-5 px-lg-5">
                        <p class="fs-6 fw-medium text-wrap text-sm-start">
                            The Social Orientation and Community Involvement (SOCI) is a department that manages, coordinates, and supervises all activities related to Social Orientation and Community Involvement of Asian College-Dumaguete. SOCI aims to provide acceptable, affordable, attainable, and sustainable community projects and programs.
                        </p>
                        <a href="{{ route('volunteer.about') }}" class="btn btn-success rounded-pill shadow fw-semibold fs-6">Read More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- EVENTS SECTION -->
    <div class="home-events" 
        style="background: url('{{ asset('images/sample-event-images/sample-event2.svg') }}') no-repeat center center/cover;">
        <div class="container py-5">
            <div class="row g-4" id="eventItems">
                @foreach($events as $event)
                <div class="col-12 col-sm-6 col-md-4"> <!-- Responsive Columns -->
                    <a href="{{ route('volunteer.eventDetails', $event->id) }}" class="event-link" style="text-decoration: none; color: inherit;">
                        <div class="sample-events card event-box h-100 shadow-sm">
                            <div class="card-body text-center p-2">
                                <div class="day-container mx-auto mb-2">
                                    <p class="m-0">{{ \Carbon\Carbon::parse($event->event_date)->format('j') }}</p>
                                </div>
                                <img src="{{ asset('images/'.strtolower($event->category).'.png') }}" 
                                    class="card-img-top rounded-top" 
                                    alt="{{ $event->category }} Image" 
                                    style="object-fit: cover; width: 100%; height: 200px;">
                                <div class="event-info p-2">
                                    <ul class="list-unstyled mb-2 text-start">
                                        <li class="text-truncate"><i class="fas fa-calendar-alt"></i> <strong>Title:</strong> {{ $event->title }}</li>
                                        <li class="text-truncate"><i class="fas fa-calendar-alt"></i> <strong>Type:</strong> {{ $event->category }}</li>
                                        <li><i class="fas fa-calendar-day"></i> <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</li>
                                        <li><i class="fas fa-clock"></i> <strong>Time:</strong> {{ $event->start }} - {{ $event->end }}</li>
                                        <li class="text-truncate"><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> {{ $event->event_location }}</li>   
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a id="event-button" href="{{route('volunteer.calendar')}}" class="btn btn-success rounded-pill shadow fw-semibold fs-6 px-4 py-2">View More Events</a>
            </div>
        </div>
    </div>
    <!-- MISSION AND VISION -->
    <div class="mission-vision" 
        style="
        background: url('{{ asset('images/carousel-images/carousel-img2.png') }}') no-repeat center center/cover;">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 col-lg-6 my-4 px-lg-5">
                    <div class="mv-box mx-5 px-5 py-4 h-auto rounded-4 d-flex flex-column justify-content-center animate-on-scroll zoom-in">
                        <h2 class="text-center pb-3">Our Mission</h2>
                        <p><i class="bi bi-quote"></i>To be an agent of community transformation by fostering relevant programs through volunteerism and strong community engagement.<i class="bi bi-quote"></i></p>
                    </div>
                </div>
                <div class="col-12 col-lg-6 my-4 px-lg-5">
                    <div class="mv-box mx-5 px-5 py-4 h-auto rounded-4 d-flex flex-column justify-content-center animate-on-scroll zoom-in">
                        <h2 class="text-center pb-3">Our Vision</h2>
                        <p><i class="bi bi-quote"></i>To provide sustainable programs in order to respond to the present and future needs of the community.<i class="bi bi-quote"></i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BECOME A MEMBER SECTION -->
    <div class="application-preview" 
        style="
        background: url('{{ asset('images/home-application-images/volunteer-application-bg.svg') }}') no-repeat center center/cover;">
        <div class="container h-100 d-flex justify-content-center align-items-center">
                <div class="application-preview-box text-center">
                    <p class="fs-1 px-4 pb-3">MAKE A DIFFERENCE TODAY! BECOME A VOLUNTEER</p>
                    <!-- <button class="btn btn-primary rounded-pill text-white shadow fw-semibold px-3 py-2 fs-6" onclick="window.location='{{ route('application') }}'">Application Form</button> -->
                </div>
        </div>
    </div>
@endsection
