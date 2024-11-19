<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Public App')</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <!-- Font CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- AJAX CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
        @yield('styles')
    </head>
    <body> 
            <!-- Navigation bar for logo and for Home Page general audience view -->
            <nav class="navbar navbar-light navbar-expand-lg bg-body-tertiary" data-bs-theme="light">
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
                            <a class="navbar-brand" href="{{ route('index') }}">
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
                                    <a class="nav-link" aria-current="page" href="{{ route('index') }}">Home</a>
                                </li>
                                <li class="nav-item mx-3">
                                    <a class="nav-link" href="{{ route('calendar') }}">Calendar</a>
                                </li>
                                <li class="nav-item mx-3">
                                    <a class="nav-link" href="{{ route('gallery') }}">Gallery</a>
                                </li>
                                <li class="nav-item mx-3">
                                    <a class="nav-link" href="{{ route('about') }}">
                                        <img src="{{ asset('images/aboutus.png') }}" class="logo" alt="Logo" width="20" height="20">
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
        <main>
            @yield('content')
        </main>

        <br><br><br>
        <!-- footer -->
        @include('layouts.footer')
        <!-- Custom JS -->
        <script src="{{ asset('js/index.js') }}"></script>
        <!-- JQuery JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- AJAX JS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Popper JS -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- FullCalendar -->
        <script src="https://cdn.jsdelivr.net/combine/npm/fullcalendar@6.1.15,npm/fullcalendar@6.1.15/index.global.min.js"></script>
        @yield('scripts')
    </body>
</html>