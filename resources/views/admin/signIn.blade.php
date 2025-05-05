<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap">
    <style>
        .password-fieldset {
            position: relative;
            padding-right: .8em;
            /* space for the eye icon */
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 2.5em;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 0.5em;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: #666;
        }

        .toggle-password:focus {
            outline: none;
        }

        .toggle-password svg {
            display: block;
        }
    </style>
</head>

<body style="padding: 0px;">
    <!-- div for Sign In Page for Admin-->
    <div id="signinDiv">
        <div class="divSignIn">
            <div style="display: flex; align-items: center;">
                <img src="{{ asset('images/LOGO1.png') }}" alt="Logo"
                    style="margin-left: 15px; margin-top: 10px; width: 64px; height: 64px;">
                <h1 class="signinWelcome" style="text-align: center;">Welcome Back!</h1>
            </div>

            <div id="signInForm">
                <form action="{{ route('admin.signin') }}" method="POST">
                    @csrf
                    <fieldset class="custom-input">
                        <legend>Email Address</legend>
                        <input type="text" name="email" value="{{ old('email') }}">
                    </fieldset>
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <br>
                    <fieldset class="custom-input password-fieldset">
                        <legend>Password</legend>
                        <div class="password-wrapper">
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password">
                            <button type="button" class="toggle-password" aria-label="Show password">
                                <!-- You can swap this SVG for any eye icon you like -->
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.333-8-5.333S0 8 0 8s3 5.333 8 5.333S16 8 16 8z" />
                                    <circle cx="8" cy="8" r="2.667" />
                                </svg>
                            </button>
                        </div>
                    </fieldset>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <p class="forgotpass">Forgot Password?</p>
                    <button id="logIn" type="submit">Log In</button>
                </form>
                <p class="bannertextSign">Not a SOCI member yet? <a href="{{ route('application') }}"
                        class="signUpStyle">Click here to apply!</a></p>
            </div>
        </div>
        <div style="width: 50%; text-align: center;">
            <img src="{{ asset('images/bannerImgs.png') }}" class="bannerImg" alt="Banner Image" width="80%">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pwdInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.toggle-password');
            const eyeIcon = document.getElementById('eye-icon');

            toggleBtn.addEventListener('click', function () {
                const isMasked = pwdInput.type === 'password';
                pwdInput.type = isMasked ? 'text' : 'password';
                // Optionally swap the icon (filled vs. slashed eye)
                eyeIcon.innerHTML = isMasked
                    ? '<path d="M1 8s3-5.333 7-5.333S15 8 15 8s-3 5.333-7 5.333S1 8 1 8zm7-1.333a1.333 1.333 0 1 0 0 2.667 1.333 1.333 0 0 0 0-2.667z"/>'
                    : '<path d="M0 0l16 16m-1-1a8.01 8.01 0 0 0 1-1s-3-5.333-8-5.333S0 8 0 8s3 5.333 8 5.333c1.79 0 3.46-.572 4.88-1.537"/>'
                    ;
                toggleBtn.setAttribute(
                    'aria-label',
                    isMasked ? 'Hide password' : 'Show password'
                );
            });
        });
    </script>
    <script src="{{ asset('js/index.js') }}"></script>
</body>

</html>