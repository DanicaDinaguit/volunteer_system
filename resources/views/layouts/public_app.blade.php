<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Public App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
</head>
<body> 
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

    <main>
        @yield('content')
    </main>

    <br><br><br>
    <!-- footer -->
    @include('layouts.footer')
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>