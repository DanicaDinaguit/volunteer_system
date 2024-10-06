@extends('layouts.admin_app')

@section('title', 'Admin View Membership Application')

@section('content')
<style>
    .nav-tabs .nav-link {
        color: #000; /* Set a default visible text color for inactive tabs */
    }

    .nav-tabs .nav-link.active {
        color: #fff; /* Set color for the active tab */
        background-color: #007bff; /* Set background color for the active tab */
    }

    .nav-tabs .nav-link:hover {
        color: #007bff; /* Color on hover for inactive tabs */
    }

    .card {
        border: 1px solid #e0e0e0; /* Light border */
        border-radius: 10px; /* Rounded corners */
    }

    .card h5 {
        font-size: 1.3rem; /* Slightly smaller heading */
        margin-bottom: 1rem; /* Spacing below heading */
    }

    .card ul {
        list-style-type: none; /* No bullet points */
        padding: 0; /* Remove padding */
    }

    .card li {
        padding: 10px; /* Add some padding for list items */
        background-color: #f9f9f9; /* Light background for better separation */
        border-radius: 5px; /* Rounded corners for list items */
        margin-bottom: 10px; /* Space between items */
    }

    .card span {
        font-weight: 400; /* Regular font weight */
        color: #555; /* Muted text color for details */
    }

    .btn {
        font-weight: bold; /* Bold button text */
        padding: 10px 15px; /* Increase button padding */
        border-radius: 20px; /* More rounded buttons */
    }

    .btn:hover {
        opacity: 0.9; /* Subtle hover effect */
    }

    h2 {
        font-size: 1.8rem; /* Slightly smaller than before */
        margin-bottom: 20px; /* More space below the heading */
    }

    .search-container {
        width: 300px; /* Fixed width for search input */
    }

    .search-container input {
        border-radius: 20px 0 0 20px; /* Rounded corners */
    }

    .search-container button {
        border-radius: 0 20px 20px 0; /* Rounded corners */
        padding: 10px 15px; /* Padding adjustments */
    }

    .list-group-item {
        border-radius: 10px; /* Rounded corners for list items */
    }

    .list-group-item:hover {
        background-color: #f0f0f0; /* Light hover effect */
    }
    #applicant-details-pending, 
    #applicant-details-approved, 
    #applicant-details-rejected {
        display: block !important;
    }
</style>

<div style="margin-top: 100px; ">
    <div id="view-application" class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h4 class="fw-bold mb-3" style="font-size: 1.5rem;">All Membership Applications</h4>

            <!-- Search Container with SVG Icon -->
            <div class="search-container d-flex align-items-center">
                <input type="text" id="search-field" class="form-control me-2" placeholder="Search applications..." aria-label="Search">
                <button class="btn btn-outline-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>
            <!-- Add Application Button -->
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addApplicationModal">Add Application</button>
        </div>

        <!-- Tabs for filtering applications -->
        <ul class="nav nav-tabs mb-3" id="application-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending-applications">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="approved-tab" data-bs-toggle="tab" href="#approved-applications">Approved</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="rejected-tab" data-bs-toggle="tab" href="#rejected-applications">Rejected</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Pending Applications -->
            <div class="tab-pane fade show active" id="pending-applications">
                <div class="row">
                    <div class="col-lg-4 col-md-5 mb-4">
                        <div class="list-group">
                            @foreach($pendingApplicants as $applicant)
                                <button class="list-group-item list-group-item-action applicant-toggle" data-id="{{ $applicant->memberApplicationID }}" data-status="pending">
                                    {{ $applicant->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div id="applicant-details-pending" class="card shadow-sm p-4" style="display:block;">
                            <h5 class="fw-bold mb-4 text-primary">Applicant Details</h5>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <strong>Full Name:</strong>
                                    <span id="detail-name" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Age:</strong>
                                    <span id="detail-age" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Gender:</strong>
                                    <span id="detail-gender" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Phone Number:</strong>
                                    <span id="detail-phone_number" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Email Address:</strong>
                                    <span id="detail-email_address" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Address:</strong>
                                    <span id="detail-address" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Religion:</strong>
                                    <span id="detail-religion" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Citizenship:</strong>
                                    <span id="detail-citizenship" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Civil Status:</strong>
                                    <span id="detail-civil_status" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>College:</strong>
                                    <span id="detail-college" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Course:</strong>
                                    <span id="detail-course" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Year Level:</strong>
                                    <span id="detail-year_level" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>School ID:</strong>
                                    <span id="detail-schoolID" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>High School:</strong>
                                    <span id="detail-high_school" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Elementary:</strong>
                                    <span id="detail-elementary" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Reasons for Joining:</strong>
                                    <span id="detail-reasons_for_joining" class="text-muted"></span>
                                </li>
                            </ul>

                            <div class="d-flex justify-content-start mt-4">
                                <button type="button" class="approve-btn btn btn-success me-3 rounded-pill" data-id="">Approve</button>
                                <button type="button" class="reject-btn btn btn-danger rounded-pill" data-id="">Reject</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approved Applications -->
            <div class="tab-pane fade" id="approved-applications">
                <div class="row">
                    <div class="col-lg-4 col-md-5 mb-4">
                        <div class="list-group">
                            @foreach($approvedApplicants as $applicant)
                                <button class="list-group-item list-group-item-action applicant-toggle" data-id="{{ $applicant->memberApplicationID }}" data-status="approved">
                                    {{ $applicant->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div id="applicant-details-approved" class="card shadow-sm p-4" style="display:block;">
                            <h5 class="fw-bold mb-4 text-primary">Approved Applicants</h5>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <strong>Full Name:</strong>
                                    <span id="detail-name" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Age:</strong>
                                    <span id="detail-age" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Gender:</strong>
                                    <span id="detail-gender" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Phone Number:</strong>
                                    <span id="detail-phone_number" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Email Address:</strong>
                                    <span id="detail-email_address" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Address:</strong>
                                    <span id="detail-address" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Religion:</strong>
                                    <span id="detail-religion" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Citizenship:</strong>
                                    <span id="detail-citizenship" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Civil Status:</strong>
                                    <span id="detail-civil_status" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>College:</strong>
                                    <span id="detail-college" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Course:</strong>
                                    <span id="detail-course" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Year Level:</strong>
                                    <span id="detail-year_level" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>School ID:</strong>
                                    <span id="detail-schoolID" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>High School:</strong>
                                    <span id="detail-high_school" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Elementary:</strong>
                                    <span id="detail-elementary" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Reasons for Joining:</strong>
                                    <span id="detail-reasons_for_joining" class="text-muted"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected Applications -->
            <div class="tab-pane fade" id="rejected-applications">
                <div class="row">
                    <div class="col-lg-4 col-md-5 mb-4">
                        <div class="list-group">
                            @foreach($rejectedApplicants as $applicant)
                                <button class="list-group-item list-group-item-action applicant-toggle" data-id="{{ $applicant->memberApplicationID }}" data-status="pending">
                                    {{ $applicant->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div id="applicant-details-rejected" class="card shadow-sm p-4" style="display:block;">
                            <h5 class="fw-bold mb-4 text-primary">Rejected Applicants</h5>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2">
                                    <strong>Full Name:</strong>
                                    <span id="detail-name" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Age:</strong>
                                    <span id="detail-age" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Gender:</strong>
                                    <span id="detail-gender" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Phone Number:</strong>
                                    <span id="detail-phone_number" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Email Address:</strong>
                                    <span id="detail-email_address" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Address:</strong>
                                    <span id="detail-address" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Religion:</strong>
                                    <span id="detail-religion" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Citizenship:</strong>
                                    <span id="detail-citizenship" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Civil Status:</strong>
                                    <span id="detail-civil_status" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>College:</strong>
                                    <span id="detail-college" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Course:</strong>
                                    <span id="detail-course" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Year Level:</strong>
                                    <span id="detail-year_level" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>School ID:</strong>
                                    <span id="detail-schoolID" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>High School:</strong>
                                    <span id="detail-high_school" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Elementary:</strong>
                                    <span id="detail-elementary" class="text-muted"></span>
                                </li>
                                <li class="mb-2">
                                    <strong>Reasons for Joining:</strong>
                                    <span id="detail-reasons_for_joining" class="text-muted"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Application Modal -->
<div class="modal fade" id="addApplicationModal" tabindex="-1" aria-labelledby="addApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addApplicationModalLabel">Add New Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('application.submit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Tab navigation -->
                    <ul class="nav nav-tabs" id="applicationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-info-tab" data-bs-toggle="tab" data-bs-target="#personal-info" type="button" role="tab" aria-controls="personal-info" aria-selected="true">Personal Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="address-info-tab" data-bs-toggle="tab" data-bs-target="#address-info" type="button" role="tab" aria-controls="address-info" aria-selected="false">Address Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="education-info-tab" data-bs-toggle="tab" data-bs-target="#education-info" type="button" role="tab" aria-controls="education-info" aria-selected="false">Education Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reason-info-tab" data-bs-toggle="tab" data-bs-target="#reason-info" type="button" role="tab" aria-controls="reason-info" aria-selected="false">Reason for Joining</button>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="applicationTabsContent">
                        <!-- Personal Information -->
                        <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="personal-info-tab">
                            <div class="mb-3">
                                <label for="applicantName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="applicantName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantAge" class="form-label">Age</label>
                                <input type="number" class="form-control" id="applicantAge" name="age" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantGender" class="form-label">Gender</label>
                                <select class="form-select" id="applicantGender" name="gender" required>
                                    <option value="" disabled selected>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="applicantEmail" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="applicantEmail" name="email_address" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantPhone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="applicantPhone" name="phone_number" required>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="tab-pane fade" id="address-info" role="tabpanel" aria-labelledby="address-info-tab">
                            <div class="mb-3">
                                <label for="applicantAddress" class="form-label">Address</label>
                                <input type="text" class="form-control" id="applicantAddress" name="address" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantReligion" class="form-label">Religion</label>
                                <input type="text" class="form-control" id="applicantReligion" name="religion" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantCitizenship" class="form-label">Citizenship</label>
                                <input type="text" class="form-control" id="applicantCitizenship" name="citizenship" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantCivilStatus" class="form-label">Civil Status</label>
                                <select class="form-select" id="applicantCivilStatus" name="civil_status" required>
                                    <option value="" disabled selected>Select Civil Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Education Information -->
                        <div class="tab-pane fade" id="education-info" role="tabpanel" aria-labelledby="education-info-tab">
                            <div class="mb-3">
                                <label for="applicantCollege" class="form-label">College</label>
                                <input type="text" class="form-control" id="applicantCollege" name="college" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantCourse" class="form-label">Course</label>
                                <input type="text" class="form-control" id="applicantCourse" name="course" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantYearLevel" class="form-label">Year Level</label>
                                <select class="form-select" id="applicantYearLevel" name="year_level" required>
                                    <option value="" disabled selected>Select Year Level</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                    <option value="5th Year">5th Year</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="applicantSchoolID" class="form-label">School ID</label>
                                <input type="text" class="form-control" id="applicantSchoolID" name="schoolID" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantHighSchool" class="form-label">High School</label>
                                <input type="text" class="form-control" id="applicantHighSchool" name="high_school" required>
                            </div>
                            <div class="mb-3">
                                <label for="applicantElementary" class="form-label">Elementary</label>
                                <input type="text" class="form-control" id="applicantElementary" name="elementary" required>
                            </div>
                        </div>

                        <!-- Reason for Joining -->
                        <div class="tab-pane fade" id="reason-info" role="tabpanel" aria-labelledby="reason-info-tab">
                            <div class="mb-3">
                                <label for="applicantReasonsForJoining" class="form-label">Reasons for Joining</label>
                                <textarea class="form-control" id="applicantReasonsForJoining" name="reasons_for_joining" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
