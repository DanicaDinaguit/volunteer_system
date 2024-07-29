<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer System Home Page</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
</head>
<body>
    <div>
        <!-- Navigation bar for logo and for Home Page general audience view -->
        <div id="navdiv">
            <nav>
                <img src="{{ asset('images/LOGO.png') }}" class="logo" alt="Logo">
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="{{ route('index') }}">
                            <img src="{{ asset('images/home.png') }}" alt="Home Icon">
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('calendar') }}">
                            <img src="{{ asset('images/calendar.png') }}" alt="Calendar Icon">
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('gallery') }}">
                            <img src="{{ asset('images/gallery.png') }}" alt="Gallery Icon">
                            <span>Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}">
                            <img src="{{ asset('images/about.png') }}" alt="About Us Icon">
                            <span>About Us</span>
                        </a>
                    </li>
                </ul>  
            </nav>
        </div>

        <!-- div for Banner -->
        <div id="banner">
            <div>
                <h1 class="volunteerneeded">Volunteer<br/><br/><br/><br/><br/>Needed</h1>
                <hr>
                <p class="bannertext">Come join us in helping those who need help with <br> health and education services.</p>
                <img class="unite" src="{{ asset('images/uniteasone.png') }}" alt="Unite as One" >
            </div>
            <div>
                <img src="{{ asset('images/bannerImg.png') }}" class="bannerImg" alt="Banner Image">
            </div>
        </div>

        <!-- div for events flexed -->
        <div>
            <div id="eventItems">
                @foreach($events as $event)
                    <div class="event-box">
                        <div class="day-container">
                            <p>{{ \Carbon\Carbon::parse($event->event_date)->format('l') }}</p>
                        </div>
                        <img src="{{ asset('images/event-image.jpg') }}" alt="Event Image">
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
        <br><br><br>
        <!-- footer -->
        <footer id="footer">
            <div id="socMedIcons">
                <img src="{{ asset('images/Vector.png') }}" alt="Facebook">
                <img src="{{ asset('images/Instagram.png') }}" alt="Instagram">
                <img src="{{ asset('images/Email.png') }}" alt="Email">
            </div>
            <div class="footerTextContainer">
                <p class="footerText">Join our mission. Volunteer, Help, Advocate. Get Started Today.</p>
                <a class="footerText" href="">Privacy Notice</a>
                <a class="footerText" href="">Terms of Use</a>
                <a class="footerText" href="">Help Center</a>
                <a class="footerText" href="">Contact Us</a>
            </div>
            © 2024 DumaVolunteer Hub. All rights reserved.
        </footer>
    </div>
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>