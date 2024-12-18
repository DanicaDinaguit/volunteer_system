<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
</head>
<body>
    <div id="signinDiv">
        <div>
            <div style="display: flex; align-items: center;">
                <img src="{{ asset('images/LOGO1.png') }}" alt="Logo" style="margin-left: 15px; margin-top: 10px; width: 64px; height: 64px;">
                <h1 class="signinWelcome" style="text-align: center;">Don't miss out on an opportunity.</h1>
            </div>
            <div id="signUpForm">
                @if(session('success'))
                    <div>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div style="color: red;">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('volunteer.signup') }}" method="POST">
                    @csrf
                    <fieldset class="custom-input">
                        <legend>First name</legend>
                        <input type="text" name="first_name" required>
                    </fieldset>
                    <fieldset class="custom-input">
                        <legend>Middle name</legend>
                        <input type="text" name="middle_name" required>
                    </fieldset>
                    <fieldset class="custom-input">
                        <legend>Last name</legend>
                        <input type="text" name="last_name" required>
                    </fieldset>
                    <fieldset class="custom-input">
                        <legend>Student ID</legend>
                        <input type="text" name="studentID" required>
                    </fieldset>
                    <fieldset class="custom-input">
                        <legend>Email Address</legend>
                        <input type="email" name="email" required>
                    </fieldset>
                    <fieldset class="custom-input">
                        <legend>Password</legend>
                        <input type="password" name="password" required>
                    </fieldset>
                    <br>
                    <button type="submit" id="logIn">Sign Up</button>
                    <hr>
                    <button class="custom-input">Continue with Google</button>
                    <p class="bannertextSign">Not a SOCI member yet? <a href="{{ route('application') }}" class="signUpStyle">Click here to apply!</a></p>
                </form>
            </div>
        </div>
        <div>
            <img src="{{ asset('images/bannerImg.png') }}" class="bannerImg" alt="Banner Image">
        </div>
    </div><br>
    @include('layouts.footer')
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
