@extends('layouts.public_app')

@section('title', 'Public Home')
    
@section('content')
    <!-- div for Banner -->
    <div  style="margin-top; 100px;">
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
                    <div id="banner">  
                    </div>
                    <div id="hero-content">
                        <img src="{{ asset('images/LOGO1.png') }}">
                    </div>
                    <div alt="First Slide">
                        <h1 id="hero-title">LET'S MAKE THE WORLD A BETTER PLACE</h1>
                    </div>
                    
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div id="banner">  
                    </div>
                    <div id="hero-content">
                        <img src="{{ asset('images/LOGO1.png') }}" >
                    </div>
                    <div alt="Second Slide">
                        <h1 id="hero-title">JOIN US IN OUR MISSION</h1>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <img src="{{ asset('images/LOGO1.png') }}" alt="Third Slide">
                    <div>
                        <h1 id="hero-title">TOGETHER WE CAN MAKE A CHANGE</h1>
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

    <div style="background: #D98641; padding: 15px;">
        <h2 style="color: #FFF; text-align: center; font-family: Oswald; font-size: 40px; font-style: normal; font-weight: 600;">SOCIAL ORIENTATION AND COMMUNITY INVOLVEMENT</h2>
        <div style="display: flex; justify-content: center; gap: 5%; margin-top: 20px;">
            <img src="{{asset('images/bannerImg.png')}}" alt="" style="width: 510px; height: 282px;border-radius: 14px;">
            <div style="">
                <p style="align-text: left; width: 591px;color: #FFF; font-family: Oswald; font-size: 18px; font-style: normal; font-weight: 500;">The Social Orientation and Community Involvement (SOCI) is a department that manages, coordinates, and supervises all activities related to Social Orientation and Community Involvement of Asian College-Dumaguete. SOCI  aims to provide acceptable, affordable, attainable, and sustainable community projects and programs.</p>
                <button style="width: 111px; height: 41px; border-radius: 13px; background: #6F833F; color: white; border: none;" href="{{route('about')}}">Read More</button>
            </div>
        </div>
    </div>
    <!-- div for events flexed -->
    <div>
        <div id="eventItems">
            @foreach($events as $event)
                <div class="event-box">
                    <div class="day-container">
                        <p>{{ \Carbon\Carbon::parse($event->event_date)->format('j') }}</p>
                    </div>
                    <img src="{{ asset('images/event-image.jpg') }}" alt="Event Image">
                    <div class="event-info">
                        <h2>{{ $event->title }}</h2>
                        <p>{{ $event->event_location }}</p>
                        <p>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
                        <p>{{ $event->start }} - {{ $event->end }}</p>
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
    <div class="bg-home">
        <div>
        <h2>Other 
            Programs</h2>
        <ul>
            <li>Values Formation and Partnership</li>
            <li>Skills/Livelihood Training</li>
            <li>Health and Environment</li>
            <li>Education and Technology</li>
        </ul>
        </div>
        <div>
            <h4>Become a member</h4>
            <p>Join our community today
                and make difference</p>
            <p>Apply and start your journey 
                as a valued volunteer!</p>
            <button onclick="window.location='{{ route('application') }}'">Application Form</button>
        </div>
    </div>      
@endsection


        
        
    