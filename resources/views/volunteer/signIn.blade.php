<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
</head>
<body>
    <!-- div for Sign In for Volunteers-->
    <div id="signinDiv">
        <div>
            <div style="display: flex; align-items: center;">
                <img src="{{ asset('images/LOGO.png') }}" alt="Logo" style="margin-left: 15px; margin-top: 10px;">
                <h1 class="signinWelcome" style="text-align: center;">Welcome Back!</h1>
            </div>

            <div id="signInForm">
                <form method="POST" action="{{ route('volunteer.signIn.submit') }}">
                    @csrf
                    <fieldset class="custom-input">
                        <legend>Email Address</legend>
                        <input type="text" name="email" value="{{ old('email') }}" required>
                    </fieldset>
                    <br>
                    <fieldset class="custom-input">
                        <legend>Password</legend>
                        <input type="password" name="password" required>
                    </fieldset>

                    <p class="forgotpass"><a href="#">Forgot Password?</a></p>
                    <button type="submit" id="logIn">Log In</button>
                    <p class="bannertextSign">Not a SOCI member yet? <a href="{{ route('application') }}" class="signUpStyle">Click here to apply!</a></p>
                </form>
            </div>
        </div>

        <div>
            <img src="{{ asset('images/bannerImg.png') }}" class="bannerImg" alt="Banner Image">
        </div>
    </div>
    <footer id="footer">
        <div id="socMedIcons">
            <img src="{{ asset('images/Vector.png') }}" alt="Facebook">
            <img src="{{ asset('images/Instagram.png') }}" alt="Instagram">
            <img src="{{ asset('images/Email.png') }}" alt="Email">
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
</body>
</html>
