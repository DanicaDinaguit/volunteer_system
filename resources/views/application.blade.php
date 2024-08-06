@extends('layouts.public_app')

@section('title', 'Public Home')
    
@section('content')
    <div id="form">
        <form method="POST" action="{{ route('application.submit') }}">
            @csrf
            <h1>Application Form for SOCI Student Volunteer</h1>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-container">
                <h2>A. PERSONAL INFORMATION</h2>
                <div class="grid-row">
                    <label for="name">NAME</label>
                    <label for="phone_number">MOBILE NUMBER</label>
                    <label for="email-address">EMAIL ADDRESS</label>
                    <label for="age">AGE</label>
                </div>
                <div class="grid-row">
                    <input type="text" id="name" name="name" required>
                    <input type="text" id="phone_number" name="phone_number" required>
                    <input type="email" id="email_address" name="email_address" required>
                    <input type="number" id="age" name="age" required>
                </div><br>
                <div class="grid-row">
                    <label for="address">ADDRESS</label>
                    <label for="religion">RELIGION</label>
                    <label for="gender">GENDER</label>
                </div>
                <div class="grid-row">
                    <input type="text" id="address" name="address" required>
                    <input type="text" id="religion" name="religion" required>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div><br>
                <div class="grid-row">
                    <label for="citizenship">CITIZENSHIP</label>
                    <label for="civil_status">CIVIL STATUS</label>
                </div>
                <div class="grid-row">
                    <input type="text" id="citizenship" name="citizenship" required>
                    <select id="civil_status" name="civil_status" required>
                        <option value="">Select Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>

                <h2>B. SCHOOL ATTENDED</h2>
                <div class="grid-row">
                    <label for="college">COLLEGE</label>
                    <label for="course">COURSE</label>
                </div>
                <div class="grid-row">
                    <input type="text" id="college" name="college" required>
                    <input type="text" id="course" name="course" required>
                </div><br>
                <div class="grid-row">
                    <label for="year_level">YEAR LEVEL</label>
                    <label for="schoolID">SCHOOL ID</label>
                </div>
                <div class="grid-row">
                    <select id="year_level" name="year_level" required>
                        <option value="">Select Year</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                        <option value="5th Year">5th Year</option>
                    </select>
                    <input type="text" id="schoolID" name="schoolID" required>
                </div><br>
                <div class="grid-row">
                    <label for="high_school">HIGH SCHOOL</label>
                    <label for="elementary">ELEMENTARY</label>
                </div>
                <div class="grid-row">
                    <input type="text" id="high_school" name="high_school" required>
                    <input type="text" id="elementary" name="elementary" required>
                </div>

                <h2>C. REASONS FOR JOINING AS SOCI VOLUNTEER:</h2>
                <textarea id="reasons_for_joining" name="reasons_for_joining" required></textarea><br><br>
            </div>
            <input type="submit" value="Submit">
        </form>
    </div>
@endsection