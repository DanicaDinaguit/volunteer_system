@extends('layouts.admin_app')

@section('title', 'Admin View Membership Application')

@section('content')
    <div id="view-application">
        <div class="application-view" style="margin-top: 115px;">
            <div><h2>All Membership Applications</h2></div>
            
            <!-- Collapsible Search Icon -->
            <div class="search-container">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="54" viewBox="0 0 56 54" fill="none" id="search-icon">
                    <g filter="url(#filter0_d_794_1612)">
                        <path d="M44.8575 38.5662L30.8694 25.1152C29.7593 25.9692 28.4826 26.6453 27.0393 27.1435C25.5961 27.6417 24.0604 27.8908 22.4322 27.8908C18.3986 27.8908 14.9852 26.5471 12.192 23.8597C9.39881 21.1724 8.00148 17.8901 8 14.0128C8 10.134 9.39733 6.85172 12.192 4.16579C14.9866 1.47986 18.4 0.136189 22.4322 0.134766C26.4658 0.134766 29.8799 1.47844 32.6745 4.16579C35.4692 6.85314 36.8658 10.1355 36.8643 14.0128C36.8643 15.5785 36.6053 17.0553 36.0872 18.4431C35.5691 19.8309 34.866 21.0585 33.9779 22.1261L47.966 35.577L44.8575 38.5662ZM22.4322 23.6206C25.2076 23.6206 27.567 22.6869 29.5106 20.8194C31.4541 18.9519 32.4251 16.683 32.4237 14.0128C32.4237 11.3439 31.4526 9.07576 29.5106 7.20828C27.5685 5.3408 25.2091 4.40634 22.4322 4.40492C19.6567 4.40492 17.298 5.33937 15.356 7.20828C13.4139 9.07718 12.4421 11.3453 12.4407 14.0128C12.4407 16.6816 13.4124 18.9505 15.356 20.8194C17.2995 22.6883 19.6582 23.622 22.4322 23.6206Z" fill="#AB2695"/>
                    </g>
                    <defs>
                        <filter id="filter0_d_794_1612" x="0.387434" y="0.134766" width="55.191" height="53.6568" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                        <feOffset dy="7.61257"/>
                        <feGaussianBlur stdDeviation="3.80628"/>
                        <feComposite in2="hardAlpha" operator="out"/>
                        <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_794_1612"/>
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_794_1612" result="shape"/>
                        </filter>
                    </defs>
                </svg>
                <input type="text" id="search-field" class="search-field" placeholder="Search...">
            </div>
        </div>

        @foreach($applicants as $applicant)
            <div class="applicants">
                <div class="applicants-name">
                    <button class="applicant-toggle" style="cursor: pointer;">{{ $applicant->name }}</button>
                </div>
                <div class="applicants-details" style="display: none;">
                    <ul>
                        <li><strong>Full Name:</strong> {{ $applicant->name }}</li>
                        <li><strong>Age:</strong> {{ $applicant->age }}</li>
                        <li><strong>Gender:</strong> {{ $applicant->gender }}</li>
                        <li><strong>Phone Number:</strong> {{ $applicant->phone_number }}</li>
                        <li><strong>Email Address:</strong> {{ $applicant->email_address }}</li>
                        <li><strong>Address:</strong> {{ $applicant->address }}</li>
                        <li><strong>Religion:</strong> {{ $applicant->religion }}</li>
                        <li><strong>Citizenship:</strong> {{ $applicant->citizenship }}</li>
                        <li><strong>Civil Status:</strong> {{ $applicant->civil_status }}</li>
                        <li><strong>College:</strong> {{ $applicant->college }}</li>
                        <li><strong>Course:</strong> {{ $applicant->course }}</li>
                        <li><strong>Year Level:</strong> {{ $applicant->year_level }}</li>
                        <li><strong>School ID:</strong> {{ $applicant->schoolID }}</li>
                        <li><strong>High School:</strong> {{ $applicant->high_school }}</li>
                        <li><strong>Elementary:</strong> {{ $applicant->elementary }}</li>
                        <li><strong>Reasons for Joining:</strong> {{ $applicant->reasons_for_joining }}</li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@endsection