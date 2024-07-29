<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">

</head>
<body>
    <div>
        <!-- navigation bar for logo and navigation Icons [Admin Home] -->
        <div id="navdiv">
            <nav>
                <img src="{{ asset('images/LOGO.png') }}" class="logo" alt="Logo">
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="{{ route('admin.Home') }}">
                            <img src="{{ asset('images/home.png') }}" alt="Home Icon">
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="calendar.html">
                            <img src="{{ asset('images/calendar.png') }}" alt="About Icon">
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.html">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Applications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.html">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.html">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.html">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Certificate</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}">
                            <img src="{{ asset('images/about.png') }}" alt="Contact Icon">
                            <span>Profile</span>
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
    </div>
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
