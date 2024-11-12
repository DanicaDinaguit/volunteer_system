<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Web Application for managing events, applications, attendance, and notifications.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
    
    <!-- Favicon for different browsers -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <!-- ICO for older browsers -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <!-- Include the Html5-qrcode library -->
    <script src="{{ asset('js/html5-qrcode.min.js')}}"></script>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('css/fullcalendar.print.min.css') }}" rel="stylesheet" media="print"> -->
</head>
<body>
    <div>
        <div id="navdiv">
            <!-- <div id="pre-navdiv"></div> -->
            <nav>
                <img src="{{ asset('images/LOGO1.png') }}" class="logo" alt="Logo">
                <ul class="nav-items">
                    <li class="nav-item">
                        <a href="{{ route('admin.Home') }}">
                            <span class="nav-text">Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.calendar') }}">
                            <span class="nav-text">Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.event')}}">
                            <span>Event</span>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.viewApplication') }}">
                            <span class="nav-text">Applications</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.messages')}}">
                            <span class="nav-text">Messages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.gallery')}}">
                            <span class="nav-text">Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.notification') }}">
                            <span class="nav-text">Notification</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#">
                            <img src="{{ asset('images/aboutus.png') }}" alt="About Us Icon">
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}">
                            <img src="{{ asset('images/profile.png') }}" alt="Profile Icon">
                        </a>
                    </li>
                </ul>  
            </nav>
        </div>
        <br>

        <main>
            @yield('content')
        </main>

        <br><br><br>
        <!-- footer -->
        @include('layouts.footer')
    </div>
    <!-- JS -->
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/combine/npm/fullcalendar@6.1.15,npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @yield('scripts')

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://raw.githubusercontent.com/mebjas/html5-qrcode/master/minified/html5-qrcode.min.js"></script>
    <script src="{{ asset('js/html5-qrcode.min.js')}}"></script>
</body>
</html>
