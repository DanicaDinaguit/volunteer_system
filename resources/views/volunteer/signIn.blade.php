<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
</head>
<body style="padding: 0px;">
    <!-- div for Sign In for Volunteers-->
    <div id="signinDiv">
        <div class="divSignIn">
            <div style="display: flex; align-items: center;">
                <img src="{{ asset('images/LOGO1.png') }}" alt="Logo" style="margin-left: 15px; margin-top: 10px; width: 64px; height: 64px;">
                <h1 class="signinWelcome" style="text-align: center;">Welcome Back!</h1>
            </div>
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

        <div style="width: 50%; text-align: center;">
            <img src="{{ asset('images/bannerImgs.png') }}" class="bannerImg" alt="Banner Image" width="80%">
        </div>
    </div>
</body>
</html>
