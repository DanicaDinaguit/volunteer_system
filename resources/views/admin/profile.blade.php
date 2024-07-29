@extends('layouts.admin_app')

@section('title', 'Admin Profile')

@section('content')
    <div style="margin-top: 110px; margin-left: 90%">Sign Out 
        <svg xmlns="http://www.w3.org/2000/svg" width="33" height="36" viewBox="0 0 33 36" fill="none">
            <g clip-path="url(#clip0_811_180)" filter="url(#filter0_d_811_180)">
                <g filter="url(#filter1_d_811_180)">
                <path d="M16 4C9.383 4 4 9.383 4 16C4 22.617 9.383 28 16 28C20.05 28 23.64 25.988 25.813 22.906L24.188 21.75C23.2656 23.0647 22.0397 24.1374 20.6142 24.8773C19.1888 25.6172 17.606 26.0023 16 26C10.465 26 6 21.535 6 16C6 10.465 10.465 6 16 6C17.6059 5.99813 19.1886 6.38343 20.614 7.12326C22.0393 7.86309 23.2653 8.93564 24.188 10.25L25.813 9.094C24.7064 7.51956 23.2369 6.2347 21.5289 5.34805C19.8209 4.4614 17.9245 3.99902 16 4ZM23.344 11.281L21.906 12.719L24.188 15H12V17H24.188L21.906 19.281L23.344 20.719L27.344 16.719L28.03 16L27.343 15.281L23.344 11.281Z" fill="#AB2695"/>
                </g>
            </g>
            <defs>
                <filter id="filter0_d_811_180" x="-4" y="0" width="40" height="40" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                <feOffset dy="4"/>
                <feGaussianBlur stdDeviation="2"/>
                <feComposite in2="hardAlpha" operator="out"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_811_180"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_811_180" result="shape"/>
                </filter>
                <filter id="filter1_d_811_180" x="0" y="4" width="32.03" height="32" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                <feOffset dy="4"/>
                <feGaussianBlur stdDeviation="2"/>
                <feComposite in2="hardAlpha" operator="out"/>
                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_811_180"/>
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_811_180" result="shape"/>
                </filter>
                <clipPath id="clip0_811_180">
                <rect width="32" height="32" fill="white"/>
                </clipPath>
            </defs>
        </svg>
    </div>

    <h1 class="profileH1">Profile Information</h1>
    <form class="profile-form" method="POST" action="{{ route('application') }}">
        @csrf
        <div id="profile">
            <div>
                <h3 class="profileName">FULL NAME</h3>
                <!-- Profile Image -->
                <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255" fill="none">
                    <g clip-path="url(#clip0_811_217)">
                        <path d="M168.11 84.7702C168.11 96.012 163.644 106.793 155.695 114.743C147.746 122.692 136.964 127.158 125.722 127.158C114.481 127.158 103.699 122.692 95.7499 114.743C87.8008 106.793 83.335 96.012 83.335 84.7702C83.335 73.5284 87.8008 62.747 95.7499 54.7978C103.699 46.8486 114.481 42.3828 125.722 42.3828C136.964 42.3828 147.746 46.8486 155.695 54.7978C163.644 62.747 168.11 73.5284 168.11 84.7702Z" fill="#AB2695"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M121.398 211.844C64.8796 209.577 19.7529 163.035 19.7529 105.961C19.7529 47.4342 67.195 -0.0078125 125.721 -0.0078125C184.248 -0.0078125 231.69 47.4342 231.69 105.961C231.69 164.487 184.248 211.929 125.721 211.929H124.27C123.309 211.929 122.352 211.901 121.398 211.844ZM57.7214 172.827C56.9291 170.551 56.6594 168.127 56.9324 165.733C57.2053 163.339 58.0139 161.038 59.2981 158.999C60.5823 156.961 62.3091 155.238 64.3504 153.958C66.3917 152.678 68.695 151.874 71.0893 151.607C112.396 147.034 139.301 147.447 180.406 151.702C182.804 151.952 185.113 152.745 187.157 154.022C189.202 155.299 190.928 157.026 192.205 159.07C193.481 161.115 194.274 163.424 194.523 165.822C194.772 168.22 194.471 170.643 193.642 172.906C211.26 155.082 221.126 131.022 221.093 105.961C221.093 53.289 178.393 10.589 125.721 10.589C73.0497 10.589 30.3498 53.289 30.3498 105.961C30.3498 132.008 40.793 155.617 57.7214 172.827Z" fill="#AB2695"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_811_217">
                        <rect width="254.324" height="254.324" fill="white"/>
                        </clipPath>
                    </defs>
                </svg><br>
                <button class="upload-picture">Upload Picture</button>
            </div>
            <div>
                <div class="profile-input">
                    <div class="grid-row">
                        <label for="first-name">First Name:</label>
                        <label for="last-name">Last Name:</label>
                    </div>
                    <div class="grid-row">
                        <input type="text" id="first-name" name="firstName" value="">
                        <input type="text" id="last-name" name="lastName" value="">
                    </div>
                    <div class="grid-row">
                        <label for="middle-name">Middle Name:</label>
                        <label for="schoolID">School ID:</label>
                    </div>
                    <div class="grid-row">
                        <input type="text" id="middle-name" name="middleName" value="">
                        <input type="text" id="schoolID" name="schoolID" value="">
                    </div>
                    <div class="grid-row">
                        <label for="email">Email Address:</label>
                        <label for="course">Course:</label>
                    </div>
                    <div class="grid-row">
                        <input type="email" id="email" name="email" value="">
                        <input type="text" id="course" name="course" value="">
                    </div>
                    <div class="grid-row">
                        <label for="email">Password:</label>
                        <label for="course">Confirm Password:</label>
                    </div>
                    <div class="grid-row">
                        <input type="password" id="password" name="password" value="">
                        <input type="password" id="password" name="password" value="">
                    </div>
                </div>  
            </div>
        </div>
        <input type="submit" value="Update Info">
    </form>          
@endsection