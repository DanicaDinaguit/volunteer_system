<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

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
                        <a href="{{ route('admin.calendar') }}">
                            <img src="{{ asset('images/calendar.png') }}" alt="About Icon">
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.event')}}">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Event</span>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.viewApplication') }}">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Applications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.messages')}}">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.gallery')}}">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.notification') }}">
                            <img src="{{ asset('images/about.png') }}" alt="Contact Icon">
                            <span>Notification</span>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
