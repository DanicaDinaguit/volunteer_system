@extends('layouts.admin_app')

@section('title', 'Home - SOCI')

@section('content')
    <!-- div for Banner -->
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
                        <h1 class="carousel-title">LET'S MAKE THE WORLD A BETTER PLACE</h1>
                    </div> 
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel-images/carousel-img2.svg') }}" class="carousel-img d-block w-100" alt="Second Slide">
                    <div class="carousel-caption d-flex flex-column align-items-center mt-5 mt-md-0">
                        <img src="{{ asset('images/LOGO1.png') }}" alt="Logo" class="carousel-logo">
                        <h1 class="carousel-title">JOIN US IN OUR MISSION</h1>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/carousel-images/carousel-img3.svg') }}" class="carousel-img d-block w-100" alt="Third Slide">
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
    <!-- Read More Section -->
    <div>
        <div class="read-more text-white py-5">
                <div class="container">
                    <h2 class="text-center fw-semibold fs-2">SOCIAL ORIENTATION AND COMMUNITY INVOLVEMENT</h2>
                    <div class="row justify-content-center align-items-center mt-5">
                        <div class="col-12 col-md-5 mb-3 mb-md-0 text-center">
                            <img src="{{ asset('images/read-more/read-more-img.png') }}" alt="Read More Image" class="img-fluid rounded animate-on-scroll zoom-in">
                        </div>
                        <div class="col-12 col-md-7">
                            <p class="fs-6 fw-medium text-wrap text-sm-start">
                                The Social Orientation and Community Involvement (SOCI) is a department that manages, coordinates, and supervises all activities related to Social Orientation and Community Involvement of Asian College-Dumaguete. SOCI aims to provide acceptable, affordable, attainable, and sustainable community projects and programs.
                            </p>
                            <a href="{{ route('about') }}" class="btn btn-success text-white shadow fw-semibold fs-6">Read More</a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- Events Section -->
    <div>
        <div class="container py-5">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="eventItems">
                @foreach($events as $event)
                <div class="col">
                    <div class="card event-box h-100">
                        <div class="card-body text-center">
                            <div class="day-container mx-auto mb-3">
                                <p class="m-0">{{ \Carbon\Carbon::parse($event->event_date)->format('j') }}</p>
                            </div>
                            <img src="{{ asset('images/event-image.jpg') }}" class="img-fluid rounded mb-3" alt="Event Image">
                            <div class="event-info">
                                <h2 class="event-title h5">{{ $event->title }}</h2>
                                <p class="mb-1 text-muted">{{ $event->event_location }}</p>
                                <p class="mb-1">{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
                                <p class="mb-0">{{ $event->start }} - {{ $event->end }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <button id="event-button" class="btn btn-secondary">View More Events</button>
            </div>
        </div>
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

    <script>
    var myCarousel = document.querySelector('#heroCarousel');
    var carousel = new bootstrap.Carousel(myCarousel, {
        interval: 8000,  // Adjust time between slides
        ride: 'carousel'
    });
</script>
@endsection


