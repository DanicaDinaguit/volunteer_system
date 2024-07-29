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
        <!-- navigation bar for logo and for Gallery general audience view -->
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
                            <img src="{{ asset('images/calendar.png') }}" alt="About Icon">
                            <span>Calendar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="gallery.html">
                            <img src="Images/gallery.png" alt="Services Icon">
                            <span>Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="about.html">
                            <img src="Images/about.png" alt="Contact Icon">
                            <span>About Us</span>
                        </a>
                    </li>
                </ul>  
            </nav>
        </div>

        <div id="gallery" style="margin-top: 130px; margin-left: 100px; margin-right: 100px;">
            <h1>Gallery of Events</h1>
            <div class="search-bar">
                <img src="Images/search-icon.png" class="search-icon" alt="Search Icon">
                <input type="text" class="search-input" placeholder="Search Year">
            </div>
        </div>

        <br><br><br>
        <!-- footer -->
        <footer id="footer">
            <div id="socMedIcons">
                <img src="Images/Vector.png" alt="Facebook">
                <img src="Images/Instagram.png" alt="Instagram">
                <img src="Images/Email.png" alt="Email">
            </div>
            <div class="footerTextContainer">
                <p class="footerText">Join our mission. Volunteer, Help, 
                    Advocate. Get Started Today.</p>
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
