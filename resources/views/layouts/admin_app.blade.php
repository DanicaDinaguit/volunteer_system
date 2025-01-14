<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Web Application for managing events, applications, attendance, and notifications.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin App')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Favicon for different browsers -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('android-chrome-512x512.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <!-- ICO for older browsers -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Font CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AJAX CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    @yield('styles')
    <!-- <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet"> -->
    <!-- <link href="{{ asset('css/fullcalendar.print.min.css') }}" rel="stylesheet" media="print"> -->
</head>
<body>
    <!-- Navigation bar for logo and for Home Page general audience view -->
    <nav class="navbar navbar-light navbar-expand-lg bg-body-tertiary fixed-top" data-bs-theme="light">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand ps-md-5" href="{{ route('index') }}">
                <img src="{{ asset('images/LOGO1.png') }}" class="logo" alt="Logo" width="55" height="55">
            </a>
            <!-- Toggle Button -->
            <button 
                class="navbar-toggler" 
                type="button" 
                data-bs-toggle="offcanvas" 
                data-bs-target="#offcanvasNavbar" 
                aria-controls="offcanvasNavbar" 
                >
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Sidebar -->
            <div
                class="sidebar offcanvas offcanvas-start"
                tabindex="-1"
                id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel"
            >
                <!-- Sidebar Header -->
                <div class="offcanvas-header border-bottom">
                    <!-- Sidebar Logo -->
                    <a class="navbar-brand" href="{{ route('admin.Home') }}">
                    <img src="{{ asset('images/LOGO1.png') }}" class="logo" alt="Logo" width="55" height="55">
                    </a>
                    <button
                        type="button"
                        class="btn-close shadow-none"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"
                    ></button>
                </div>
                <!-- Sidebar Body -->
                <div class="offcanvas-body flex-column flex-lg-row p-lg-1">
                    <ul 
                        class="navbar-nav justify-content-end fs-5 flex-grow-1 pe-md-5"
                    >
                        <li class="nav-item mx-3">
                            <a class="nav-link" aria-current="page" href="{{ route('admin.Home') }}">Home</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="{{ route('admin.calendar') }}">Calendar</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="{{ route('admin.event') }}">Event</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="{{ route('admin.viewApplication') }}">Application</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="{{ route('admin.messages') }}">Messages</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="{{ route('admin.gallery') }}">Gallery</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="{{ route('admin.notification') }}">Notification</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="{{ route('admin.about')}}">
                                <img src="{{ asset('images/aboutus.png') }}" class="logo" alt="Logo" width="20" height="20">
                            </a>
                        </li>
                        <li class="nav-item">
                        <a href="{{ route('admin.profile') }}">
                            <img src="{{ asset('images/profile.png') }}" alt="Profile Icon">
                        </a>
                    </li>
                    </ul>
                </div>
            </div>  
        </div>
    </nav>
    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn" class="btn btn-primary">
        ↑
    </button>
    <div>
        <main>
            @yield('content')
        </main>

        <br><br><br>
        <!-- footer -->
        @include('layouts.footer')
    </div>
    <!-- Custom JS -->
    <script src="{{ asset('js/index.js') }}"></script>
    <!-- JQuery JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AJAX JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/combine/npm/fullcalendar@6.1.15,npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://raw.githubusercontent.com/mebjas/html5-qrcode/master/minified/html5-qrcode.min.js"></script>
    <script src="{{ asset('js/html5-qrcode.min.js')}}"></script>
    @yield('scripts')
</body>
</html>
