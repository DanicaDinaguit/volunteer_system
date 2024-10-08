<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Volunteer App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script></div>
</head>
<body>
    
    <!-- navigation bar for logo and navigation Icons [Volunteer] -->
    <div id="navdiv">
        <nav>
            <img src="{{ asset('images/LOGO.png') }}" class="logo" alt="Logo">
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="{{ route('volunteer.Home') }}">
                        <img src="{{ asset('images/home.png') }}" alt="Home Icon">
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('volunteer.calendar') }}">
                        <img src="{{ asset('images/calendar.png') }}" alt="About Icon">
                        <span>Calendar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('volunteer.messages') }}">
                        <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                        <span>Messages</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('volunteer.notification') }}">
                        <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                        <span>Notifications</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('volunteer.gallery') }}">
                        <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                        <span>Gallery</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('volunteer.profile') }}">
                        <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                        <span>Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('volunteer.about') }}">
                        <img src="{{ asset('images/about.png') }}" alt="Contact Icon">
                        <span>About Us</span>
                    </a>
                </li>
            </ul>  
        </nav>
    </div>

    <main>
        @yield('content')
    </main>

    <br><br><br>
    <!-- footer -->
    @include('layouts.footer')
    
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>
</html>

