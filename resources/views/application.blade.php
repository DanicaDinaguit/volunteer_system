@extends('layouts.public_app')

@section('title', 'Public Application Form')

@section('content')
<div id="form">
    <form method="POST" action="{{ route('application.submit') }}" class="app-form" novalidate>
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
            <!-- Tabs Navigation -->
            <ul class="tabs">
                <li class="active" data-tab="personal-info">A. PERSONAL INFORMATION</li>
                <li data-tab="school-attended">B. SCHOOL ATTENDED</li>
                <li data-tab="reasons-for-joining">C. REASONS FOR JOINING AS SOCI VOLUNTEER</li>
            </ul>

            <!-- Personal Information Section -->
            <div class="tab-contents active" id="personal-info">
                <h2>A. PERSONAL INFORMATION</h2>
                <fieldset>
                    <div class="grid-row">
                        <div>
                            <label for="name">Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required aria-required="true" placeholder="John Doe" pattern="[A-Za-z\s]+" title="Only letters are allowed.">
                        </div>
                        <div>
                            <label for="phone_number">Mobile Number <span class="required">*</span></label>
                            <input type="tel" id="phone_number" name="phone_number" required placeholder="(123) 456-7890" pattern="\(\d{3}\) \d{3}-\d{4}" title="Format: (123) 456-7890">
                        </div>
                        <div>
                            <label for="email_address">Email Address <span class="required">*</span></label>
                            <input type="email" id="email_address" name="email_address" required placeholder="example@example.com">
                        </div>
                        <div>
                            <label for="age">Age <span class="required">*</span></label>
                            <input type="number" id="age" name="age" required min="15" max="120" placeholder="18">
                        </div>
                    </div>

                    <div class="grid-row">
                        <div>
                            <label for="address">Address <span class="required">*</span></label>
                            <input type="text" id="address" name="address" required placeholder="123 Main St, City, Country">
                        </div>
                        <div>
                            <label for="religion">Religion <span class="required">*</span></label>
                            <input type="text" id="religion" name="religion" required placeholder="Your religion">
                        </div>
                        <div>
                            <label for="gender">Gender <span class="required">*</span></label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-row">
                        <div>
                            <label for="citizenship">Citizenship <span class="required">*</span></label>
                            <input type="text" id="citizenship" name="citizenship" required placeholder="Your Citizenship">
                        </div>
                        <div>
                            <label for="civil_status">Civil Status <span class="required">*</span></label>
                            <select id="civil_status" name="civil_status" required>
                                <option value="">Select Status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- School Attended Section -->
            <div class="tab-contents" id="school-attended">
                <h2>B. SCHOOL ATTENDED</h2>
                <fieldset>
                    <div class="grid-row">
                        <div>
                            <label for="college">College <span class="required">*</span></label>
                            <input type="text" id="college" name="college" required placeholder="Your College">
                        </div>
                        <div>
                            <label for="course">Course <span class="required">*</span></label>
                            <input type="text" id="course" name="course" required placeholder="Your Course">
                        </div>
                    </div>

                    <div class="grid-row">
                        <div>
                            <label for="year_level">Year Level <span class="required">*</span></label>
                            <select id="year_level" name="year_level" required>
                                <option value="">Select Year</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="5th Year">5th Year</option>
                            </select>
                        </div>
                        <div>
                            <label for="schoolID">School ID <span class="required">*</span></label>
                            <input type="text" id="schoolID" name="schoolID" required placeholder="Your School ID">
                        </div>
                    </div>

                    <div class="grid-row">
                        <div>
                            <label for="high_school">High School <span class="required">*</span></label>
                            <input type="text" id="high_school" name="high_school" required placeholder="Your High School">
                        </div>
                        <div>
                            <label for="elementary">Elementary <span class="required">*</span></label>
                            <input type="text" id="elementary" name="elementary" required placeholder="Your Elementary School">
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- Reasons for Joining Section -->
            <div class="tab-contents" id="reasons-for-joining">
                <h2>C. REASONS FOR JOINING AS SOCI VOLUNTEER:</h2>
                <textarea id="reasons_for_joining" name="reasons_for_joining" required placeholder="Your reasons here..." rows="4"></textarea>
            </div>
        </div>

        <button type="submit" class="app-form-btn">Submit</button>
    </form>
</div>


<script>
    const tabs = document.querySelectorAll('.tabs li');
    const tabContents = document.querySelectorAll('.tab-contents');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const selectedTab = tab.dataset.tab;

            // Remove active class from all tabs and hide all contents
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to the clicked tab and show the corresponding content
            tab.classList.add('active');
            document.getElementById(selectedTab).classList.add('active');
        });
    });
</script>

@endsection
