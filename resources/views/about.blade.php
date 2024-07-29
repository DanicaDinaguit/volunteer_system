<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer System Home Page</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">

</head>
<body>
    <div>
        <!-- navigation bar for logo and button for signin -->
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
                        <a href="{{ route('gallery') }}">
                            <img src="{{ asset('images/gallery.png') }}" alt="Services Icon">
                            <span>Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}">
                            <img src="{{ asset('images/about.png') }}" alt="Contact Icon">
                            <span>About Us</span>
                        </a>
                    </li>
                </ul>  
            </nav>
        </div>

        <div id="about" style="margin-top: 130px;">
            <div class="sociDescription">
                <img src="Images/aboutLOGO.png" alt="SOCI LOGO" width="400px" height="400px">
                <p>
                    The Social Orientation and Community Involvement (SOCI) is a department that manages, coordinates, and supervises all activities related to Social Orientation and Community Involvement of Asian College-Dumaguete.  SOCI  aims to provide acceptable, affordable, attainable, and sustainable community projects and programs. It creates transparency and practices integrity in all aspects and processes of the programs and community projects it implements. The department operates within the allotted operational budget approved by the administration on the implementation of community projects. 
                </p>
                <p>  
                    SOCI commits to partner with the local community, specifically with the four Puroks of Barangay Taclobo, namely: Banikanhon, Santan, Valtimar, and Ladrico II.  It creates and conducts initial and final community surveys/assessments of implemented projects and it seeks to ensure that the community will be empowered within the next five years. 
                </p>
                <p>
                    SOCI fosters active participation in community activities, growth, and development; wherein it serves as an instrument of social and cultural transmission of change. It helps the  school develop in its faculty, staff, students, and alumni a social conscience through awareness, concern, and involvement in community development. It refuses to abide in any form of  compulsion and coercion among its volunteers and partners, as it ensures the safety and security of all volunteers at all times.
                </p>
            </div>
            <div>
                <h2>Organizational Chart</h2>
                <img src="" alt="Organizational Chart">
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
    <script src="index.js"></script>
</body>
</html>
