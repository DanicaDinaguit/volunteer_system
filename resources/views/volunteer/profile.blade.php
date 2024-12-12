@extends('layouts.volunteer_app')

@section('title', 'Volunteer Profile')

@section('content')
    <div>
        <h1 class="profileH1">Profile Information</h1>
        <!-- LOG OUT -->
        <form style="position: relative; left: 85%" method="POST" action="{{ route('volunteer.logout') }}">
            @csrf
            <button type="submit" class="sign-out-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="18" viewBox="0 0 17 18" fill="none">
                    <g filter="url(#filter0_d_1628_1703)">
                        <path d="M3.79549 3.63294H4.75988C4.82572 3.63294 4.88745 3.60413 4.92861 3.55337C5.02464 3.43677 5.12752 3.32428 5.2359 3.21728C5.67913 2.77358 6.20415 2.41999 6.78193 2.17607C7.38051 1.92325 8.02387 1.79355 8.67366 1.79471C9.33076 1.79471 9.96728 1.92366 10.5654 2.17607C11.1432 2.41999 11.6682 2.77358 12.1114 3.21728C12.5555 3.65947 12.9095 4.18357 13.154 4.76057C13.4078 5.35868 13.5354 5.99383 13.5354 6.65093C13.5354 7.30802 13.4064 7.94317 13.154 8.54128C12.9098 9.11882 12.5586 9.63874 12.1114 10.0846C11.6642 10.5304 11.1443 10.8816 10.5654 11.1258C9.96728 11.3782 9.33076 11.5071 8.67366 11.5071C8.01656 11.5071 7.38004 11.3796 6.78193 11.1258C6.20302 10.8816 5.68311 10.5304 5.2359 10.0846C5.12752 9.9762 5.02601 9.86371 4.92861 9.74848C4.88745 9.69772 4.82435 9.66892 4.75988 9.66892H3.79549C3.70907 9.66892 3.65557 9.76494 3.70358 9.83765C4.75576 11.4728 6.59673 12.5552 8.68875 12.5497C11.9756 12.5415 14.6109 9.87331 14.5779 6.59057C14.545 3.35995 11.9139 0.75213 8.67366 0.75213C6.58713 0.75213 4.75439 1.83312 3.70358 3.4642C3.65694 3.53691 3.70907 3.63294 3.79549 3.63294ZM2.57595 6.73735L4.52255 8.27378C4.59526 8.3314 4.70089 8.27927 4.70089 8.18736V7.14478H9.00838C9.06874 7.14478 9.11813 7.09539 9.11813 7.03503V6.26682C9.11813 6.20646 9.06874 6.15707 9.00838 6.15707H4.70089V5.1145C4.70089 5.02258 4.59389 4.97046 4.52255 5.02807L2.57595 6.5645C2.56283 6.57477 2.55223 6.58788 2.54493 6.60286C2.53763 6.61783 2.53384 6.63427 2.53384 6.65093C2.53384 6.66758 2.53763 6.68402 2.54493 6.69899C2.55223 6.71397 2.56283 6.72708 2.57595 6.73735Z" fill="white"/>
                    </g>
                    <defs>
                        <filter id="filter0_d_1628_1703" x="0.286552" y="0.752136" width="16.539" height="16.2921" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                        <feOffset dy="2.24726"/>
                        <feGaussianBlur stdDeviation="1.12363"/>
                        <feComposite in2="hardAlpha" operator="out"/>
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_1628_1703"/>
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_1628_1703" result="shape"/>
                        </filter>
                    </defs>
                </svg>
                Log Out 
            </button>
        </form>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form class="profile-form" method="POST" action="{{ route('volunteer.updateProfile') }}">
        @csrf
        <div id="profile">
            <div class="profilediv">
                <!-- Profile Image -->
                <svg xmlns="http://www.w3.org/2000/svg" width="83" height="83" viewBox="0 0 83 83" fill="none">
                    <path d="M58.2231 33.3178C58.2231 37.6983 56.4829 41.8995 53.3854 44.997C50.2878 48.0945 46.0867 49.8347 41.7061 49.8347C37.3256 49.8347 33.1244 48.0945 30.0269 44.997C26.9294 41.8995 25.1892 37.6983 25.1892 33.3178C25.1892 28.9372 26.9294 24.7361 30.0269 21.6385C33.1244 18.541 37.3256 16.8008 41.7061 16.8008C46.0867 16.8008 50.2878 18.541 53.3854 21.6385C56.4829 24.7361 58.2231 28.9372 58.2231 33.3178Z" fill="white"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M40.0205 82.8356C17.9973 81.9519 0.412964 63.8163 0.412964 41.5763C0.412964 18.7706 18.8995 0.283997 41.7053 0.283997C64.511 0.283997 82.9976 18.7706 82.9976 41.5763C82.9976 64.382 64.511 82.8686 41.7053 82.8686H41.1396C40.7652 82.8686 40.3922 82.8576 40.0205 82.8356ZM15.208 67.6318C14.8993 66.7451 14.7942 65.8004 14.9005 64.8676C15.0069 63.9348 15.322 63.038 15.8224 62.2437C16.3228 61.4493 16.9957 60.7779 17.7911 60.2792C18.5865 59.7805 19.484 59.4673 20.417 59.363C36.5128 57.5812 46.9969 57.7422 63.0142 59.4001C63.9484 59.4974 64.8482 59.8066 65.6448 60.3042C66.4415 60.8018 67.1142 61.4746 67.6115 62.2714C68.1089 63.0682 68.4179 63.968 68.5149 64.9023C68.612 65.8366 68.4946 66.7807 68.1716 67.6627C75.0367 60.7174 78.8811 51.3419 78.8684 41.5763C78.8684 21.052 62.2296 4.41323 41.7053 4.41323C21.1809 4.41323 4.54219 21.052 4.54219 41.5763C4.54219 51.726 8.61155 60.9259 15.208 67.6318Z" fill="white"/>
                </svg><br><br>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(event)">
                <img id="profilePreview" src="{{ asset('path/to/default/image.png') }}" alt="Profile Preview" style="display: none; width: 83px; height: 83px; border-radius: 50%; object-fit: cover;">
                
                <h3 class="profileName" style="margin-top: 0px;">{{ $volunteer->first_name }} {{ $volunteer->middle_name }} {{ $volunteer->last_name }}</h3>
                
                <br><br>
                <label for="bio" style="text-align: left;">About Me:</label><br>
                <textarea style="width: 80%;" name="aboutMe" id="bio">{{ $volunteer->aboutMe }}</textarea><br>
            </div>
            <div style="width: 60%; padding: 40px; background-color: #FFF;">
                <div class="profile-input">
                    <div class="grid-row">
                        <label for="first-name">First Name:</label>
                        <label for="last-name">Last Name:</label>
                    </div>
                    <div class="grid-row">
                        <input type="text" id="first-name" name="first_name" value="{{ $volunteer->first_name }}" readonly>
                        <input type="text" id="last-name" name="last_name" value="{{ $volunteer->last_name }}" readonly>
                    </div>
                    <div class="grid-row">
                        <label for="middle-name">Middle Name:</label>
                        <label for="schoolID">School ID:</label>
                    </div>
                    <div class="grid-row">
                        <input type="text" id="middle-name" name="middle_name" value="{{ $volunteer->middle_name }}" readonly>
                        <input type="text" id="studentID" name="studentID" value="{{ $volunteer->studentID }}" readonly>
                    </div>
                    <div class="grid-row">
                        <label for="email">Email Address:</label>
                        <label for="course">Course:</label>
                    </div>
                    <div class="grid-row">
                        <input type="email" id="email" name="email" value="{{ $volunteer->email }}" readonly>
                        <input type="text" id="course" name="course" value="{{ $volunteer->course }}" readonly>
                    </div>
                    <div class="grid-row">
                        <label for="password">Password:</label>
                        <label for="confirm-password">Confirm Password:</label>
                    </div>
                    <div class="grid-row">
                        <input type="password" id="password" name="password" readonly>
                        <input type="password" id="confirm-password" name="confirm-password" readonly>
                    </div>
                </div>  
                <input type="submit" class="save-button" value="Save Changes" style="display: none;">
                <button type="button" class="cancel-button" style="display: none;">Cancel</button>
                <button type="button" class="edit-button">Edit Profile</button>
            </div>
        </div>
    </form>  
@endsection

@section('scripts')
    <script>
        //profile page button toggle
        const editButton = document.querySelector('.edit-button');
        const saveButton = document.querySelector('.save-button'); // Updated class name
        const cancelButton = document.querySelector('.cancel-button'); // Updated class name
        const form = document.querySelector('.profile-form');
        const inputs = form.querySelectorAll('input, textarea');

        function toggleEditMode() {
            let isEditing = form.classList.contains('editing');
            inputs.forEach(input => input.readOnly = !isEditing);

            // Toggle visibility of buttons
            if (editButton) {
                editButton.style.display = isEditing ? 'none' : 'inline';
            }
            if (saveButton) {
                saveButton.style.display = isEditing ? 'inline' : 'none';
            }
            if (cancelButton) {
                cancelButton.style.display = isEditing ? 'inline' : 'none';
            }
        }

        if (editButton) {
            editButton.addEventListener('click', function() {
                form.classList.add('editing');
                toggleEditMode();
            });
        }

        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                form.classList.remove('editing');
                toggleEditMode();
            });
        }
        
        // Initialize the form state
        toggleEditMode();


        // Preview the uploaded image
        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePreview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        // Replace SVG visibility with preview if an image is selected
        const fileInput = document.getElementById('profile_image');
        const svg = document.getElementById('profileImageSVG');
        fileInput.addEventListener('change', () => {
            svg.style.display = 'none';
        });
    </script>
@endsection
