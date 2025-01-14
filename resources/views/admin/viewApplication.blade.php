@extends('layouts.admin_app')

@section('title', 'Admin View Membership Application')

@section('content')
<style>
    .nav-tabs .nav-link {
        color: #000; /* Set a default visible text color for inactive tabs */
    }

    .nav-tabs .nav-link.active {
        color: #fff !important; /* Set color for the active tab */
        background-color: #D98641; /* Set background color for the active tab */
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

<div style="display: flex; justify-content: center;">
    <div id="view-application" class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h4 class="fw mb-3" style="font-size: 1.5rem; color: #D98641;">All Membership Applications</h4>

            <!-- Search Container with SVG Icon -->
            <div class="search-container d-flex align-items-center">
                <input type="text" id="search-field" class="form-control me-2" placeholder="Search applications..." aria-label="Search">
                <div id="search-results" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
            </div>
            <!-- Add Application Button -->
            <button type="button" class="btn btn-sm" style="background: #6F833F; color: white;" data-bs-toggle="modal" data-bs-target="#addApplicationModal">Add Application</button>
        </div>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
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
                    <div class="col-lg-4 col-md-5 mb-2">
                        <div class="list-group" style="gap: 5px;">
                            @foreach($pendingApplicants as $applicant)
                                <button class="list-group-item list-group-item-action applicant-toggle" data-id="{{ $applicant->memberApplicationID }}" data-status="pending">
                                    {{ $applicant->first_name }} {{ $applicant->middle_name }} {{ $applicant->last_name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div id="applicant-details-pending" class="card shadow-sm p-3" style="display:block;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-2" style="color: #D98641;">Applicant Details</h5>
                                <a href="" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>

                            <!-- Tabs for Applicant Details Sections -->
                            <ul class="nav nav-tabs" id="applicantDetailsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="personal-info-tab" data-bs-toggle="tab" href="#personal-info" role="tab" aria-controls="personal-info" aria-selected="true" style="padding: 0.2rem 0.5rem; font-size: 0.85rem;">Personal Info</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="educational-info-tab" data-bs-toggle="tab" href="#educational-info" role="tab" aria-controls="educational-info" aria-selected="false" style="padding: 0.2rem 0.5rem; font-size: 0.85rem;">Educational Background</a>
                                </li>
                            </ul>

                            <!-- Tab Content for Each Section -->
                            <div class="tab-content" id="applicantDetailsContent">
                                <!-- Personal Info Section -->
                                <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="personal-info-tab">
                                    <ul class="list-unstyled mt-2">
                                        <li class="mb-1"><strong>Full Name:</strong> <span id="detail-name" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Age:</strong> <span id="detail-age" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Gender:</strong> <span id="detail-gender" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Phone Number:</strong> <span id="detail-phone_number" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Email Address:</strong> <span id="detail-email_address" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Address:</strong> <span id="detail-address" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Religion:</strong> <span id="detail-religion" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Citizenship:</strong> <span id="detail-citizenship" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Civil Status:</strong> <span id="detail-civil_status" class="text-muted"></span></li>
                                    </ul>
                                </div>

                                <!-- Educational Background Section -->
                                <div class="tab-pane fade" id="educational-info" role="tabpanel" aria-labelledby="educational-info-tab">
                                    <ul class="list-unstyled mt-3">
                                        <li class="mb-1"><strong>College:</strong> <span id="detail-college" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Course:</strong> <span id="detail-course" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Year Level:</strong> <span id="detail-year_level" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>School ID:</strong> <span id="detail-schoolID" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>High School:</strong> <span id="detail-high_school" class="text-muted"></span></li>
                                        <li class="mb-1"><strong>Elementary:</strong> <span id="detail-elementary" class="text-muted"></span></li>
                                        <li class="mb-2"><strong>Reasons for Joining:</strong> <span id="detail-reasons_for_joining" class="text-muted"></span></li>
                                        <div class="d-flex justify-content-start mt-4">
                                            <button type="button" class="approve-btn btn btn-success me-3 rounded-pill" data-id="">Approve</button>
                                            <button type="button" class="reject-btn btn btn-danger rounded-pill" data-id="">Reject</button>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Approved Applications -->
            <div class="tab-pane fade" id="approved-applications">
                <div class="row">
                    <div class="col-lg-4 col-md-5 mb-4">
                        <div class="list-group" style="gap: 5px;">
                            @foreach($approvedApplicants as $applicant)
                                <button class="list-group-item list-group-item-action applicant-toggle" data-id="{{ $applicant->memberApplicationID }}" data-status="approved">
                                {{ $applicant->first_name }} {{ $applicant->middle_name }} {{ $applicant->last_name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div id="applicant-details-approved" class="card shadow-sm p-4" style="display:block;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-2" style="color: #D98641;">Applicant Details</h5>
                                <a href="" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
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
                        <div class="list-group" style="gap: 5px;">
                            @foreach($rejectedApplicants as $applicant)
                                <button class="list-group-item list-group-item-action applicant-toggle" data-id="{{ $applicant->memberApplicationID }}" data-status="pending">
                                {{ $applicant->first_name }} {{ $applicant->middle_name }} {{ $applicant->last_name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-7">
                        <div id="applicant-details-rejected" class="card shadow-sm p-4" style="display:block;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="fw-bold mb-2" style="color: #D98641;">Applicant Details</h5>
                                <a href="" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
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
    <div class="modal-dialog modal-lg">
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
                            <button class="nav-link active" id="personal-infos-tab" data-bs-toggle="tab" data-bs-target="#personal-infos" type="button" role="tab" aria-controls="personal-infos" aria-selected="true">Personal Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="address-info-tab" data-bs-toggle="tab" data-bs-target="#address-info" type="button" role="tab" aria-controls="address-info" aria-selected="false">Address Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="school-info-tab" data-bs-toggle="tab" data-bs-target="#school-info" type="button" role="tab" aria-controls="school-info" aria-selected="false">School Attended</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reasons-info-tab" data-bs-toggle="tab" data-bs-target="#reasons-info" type="button" role="tab" aria-controls="reasons-info" aria-selected="false">Reason for Joining</button>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="applicationTabsContent">
                        <!-- Personal Information -->
                        <div class="tab-pane fade show active" id="personal-infos" role="tabpanel" aria-labelledby="personal-infos-tab">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name">
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_address" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email_address" name="email_address" required>
                            </div>
                            <div class="mb-3">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="tab-pane fade" id="address-info" role="tabpanel" aria-labelledby="address-info-tab">
                            <div class="mb-3">
                                <label for="street_address" class="form-label">Street Address</label>
                                <input type="text" class="form-control" id="street_address" name="street_address" required>
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="mb-3">
                                <label for="state" class="form-label">State/Province</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                            <div class="grid-row">
                                <div class="mb-3">
                                    <label for="postal_code" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                                </div>
                                <div class="mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <select id="country" name="country" class="form-select" required>
                                        <option value="" disabled selected>Select Country</option>
                                        <option value="Afghanistan">Afghanistan</option>
                                        <option value="Åland Islands">Åland Islands</option>
                                        <option value="Albania">Albania</option>
                                        <option value="Algeria">Algeria</option>
                                        <option value="American Samoa">American Samoa</option>
                                        <option value="Andorra">Andorra</option>
                                        <option value="Angola">Angola</option>
                                        <option value="Anguilla">Anguilla</option>
                                        <option value="Antarctica">Antarctica</option>
                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                        <option value="Argentina">Argentina</option>
                                        <option value="Armenia">Armenia</option>
                                        <option value="Aruba">Aruba</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Austria">Austria</option>
                                        <option value="Azerbaijan">Azerbaijan</option>
                                        <option value="Bahamas">Bahamas</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Barbados">Barbados</option>
                                        <option value="Belarus">Belarus</option>
                                        <option value="Belgium">Belgium</option>
                                        <option value="Belize">Belize</option>
                                        <option value="Benin">Benin</option>
                                        <option value="Bermuda">Bermuda</option>
                                        <option value="Bhutan">Bhutan</option>
                                        <option value="Bolivia">Bolivia</option>
                                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                        <option value="Botswana">Botswana</option>
                                        <option value="Bouvet Island">Bouvet Island</option>
                                        <option value="Brazil">Brazil</option>
                                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                                        <option value="Bulgaria">Bulgaria</option>
                                        <option value="Burkina Faso">Burkina Faso</option>
                                        <option value="Burundi">Burundi</option>
                                        <option value="Cambodia">Cambodia</option>
                                        <option value="Cameroon">Cameroon</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Cape Verde">Cape Verde</option>
                                        <option value="Cayman Islands">Cayman Islands</option>
                                        <option value="Central African Republic">Central African Republic</option>
                                        <option value="Chad">Chad</option>
                                        <option value="Chile">Chile</option>
                                        <option value="China">China</option>
                                        <option value="Christmas Island">Christmas Island</option>
                                        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                        <option value="Colombia">Colombia</option>
                                        <option value="Comoros">Comoros</option>
                                        <option value="Congo">Congo</option>
                                        <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                        <option value="Cook Islands">Cook Islands</option>
                                        <option value="Costa Rica">Costa Rica</option>
                                        <option value="Cote D'ivoire">Cote D'ivoire</option>
                                        <option value="Croatia">Croatia</option>
                                        <option value="Cuba">Cuba</option>
                                        <option value="Cyprus">Cyprus</option>
                                        <option value="Czech Republic">Czech Republic</option>
                                        <option value="Denmark">Denmark</option>
                                        <option value="Djibouti">Djibouti</option>
                                        <option value="Dominica">Dominica</option>
                                        <option value="Dominican Republic">Dominican Republic</option>
                                        <option value="Ecuador">Ecuador</option>
                                        <option value="Egypt">Egypt</option>
                                        <option value="El Salvador">El Salvador</option>
                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                        <option value="Eritrea">Eritrea</option>
                                        <option value="Estonia">Estonia</option>
                                        <option value="Ethiopia">Ethiopia</option>
                                        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                        <option value="Faroe Islands">Faroe Islands</option>
                                        <option value="Fiji">Fiji</option>
                                        <option value="Finland">Finland</option>
                                        <option value="France">France</option>
                                        <option value="French Guiana">French Guiana</option>
                                        <option value="French Polynesia">French Polynesia</option>
                                        <option value="French Southern Territories">French Southern Territories</option>
                                        <option value="Gabon">Gabon</option>
                                        <option value="Gambia">Gambia</option>
                                        <option value="Georgia">Georgia</option>
                                        <option value="Germany">Germany</option>
                                        <option value="Ghana">Ghana</option>
                                        <option value="Gibraltar">Gibraltar</option>
                                        <option value="Greece">Greece</option>
                                        <option value="Greenland">Greenland</option>
                                        <option value="Grenada">Grenada</option>
                                        <option value="Guadeloupe">Guadeloupe</option>
                                        <option value="Guam">Guam</option>
                                        <option value="Guatemala">Guatemala</option>
                                        <option value="Guernsey">Guernsey</option>
                                        <option value="Guinea">Guinea</option>
                                        <option value="Guinea-bissau">Guinea-bissau</option>
                                        <option value="Guyana">Guyana</option>
                                        <option value="Haiti">Haiti</option>
                                        <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                        <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                        <option value="Honduras">Honduras</option>
                                        <option value="Hong Kong">Hong Kong</option>
                                        <option value="Hungary">Hungary</option>
                                        <option value="Iceland">Iceland</option>
                                        <option value="India">India</option>
                                        <option value="Indonesia">Indonesia</option>
                                        <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                        <option value="Iraq">Iraq</option>
                                        <option value="Ireland">Ireland</option>
                                        <option value="Isle of Man">Isle of Man</option>
                                        <option value="Israel">Israel</option>
                                        <option value="Italy">Italy</option>
                                        <option value="Jamaica">Jamaica</option>
                                        <option value="Japan">Japan</option>
                                        <option value="Jersey">Jersey</option>
                                        <option value="Jordan">Jordan</option>
                                        <option value="Kazakhstan">Kazakhstan</option>
                                        <option value="Kenya">Kenya</option>
                                        <option value="Kiribati">Kiribati</option>
                                        <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                                        <option value="Korea, Republic of">Korea, Republic of</option>
                                        <option value="Kuwait">Kuwait</option>
                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                        <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                        <option value="Latvia">Latvia</option>
                                        <option value="Lebanon">Lebanon</option>
                                        <option value="Lesotho">Lesotho</option>
                                        <option value="Liberia">Liberia</option>
                                        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                        <option value="Liechtenstein">Liechtenstein</option>
                                        <option value="Lithuania">Lithuania</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Macao">Macao</option>
                                        <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                        <option value="Madagascar">Madagascar</option>
                                        <option value="Malawi">Malawi</option>
                                        <option value="Malaysia">Malaysia</option>
                                        <option value="Maldives">Maldives</option>
                                        <option value="Mali">Mali</option>
                                        <option value="Malta">Malta</option>
                                        <option value="Marshall Islands">Marshall Islands</option>
                                        <option value="Martinique">Martinique</option>
                                        <option value="Mauritania">Mauritania</option>
                                        <option value="Mauritius">Mauritius</option>
                                        <option value="Mayotte">Mayotte</option>
                                        <option value="Mexico">Mexico</option>
                                        <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                        <option value="Moldova, Republic of">Moldova, Republic of</option>
                                        <option value="Monaco">Monaco</option>
                                        <option value="Mongolia">Mongolia</option>
                                        <option value="Montenegro">Montenegro</option>
                                        <option value="Montserrat">Montserrat</option>
                                        <option value="Morocco">Morocco</option>
                                        <option value="Mozambique">Mozambique</option>
                                        <option value="Myanmar">Myanmar</option>
                                        <option value="Namibia">Namibia</option>
                                        <option value="Nauru">Nauru</option>
                                        <option value="Nepal">Nepal</option>
                                        <option value="Netherlands">Netherlands</option>
                                        <option value="Netherlands Antilles">Netherlands Antilles</option>
                                        <option value="New Caledonia">New Caledonia</option>
                                        <option value="New Zealand">New Zealand</option>
                                        <option value="Nicaragua">Nicaragua</option>
                                        <option value="Niger">Niger</option>
                                        <option value="Nigeria">Nigeria</option>
                                        <option value="Niue">Niue</option>
                                        <option value="Norfolk Island">Norfolk Island</option>
                                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                        <option value="Norway">Norway</option>
                                        <option value="Oman">Oman</option>
                                        <option value="Pakistan">Pakistan</option>
                                        <option value="Palau">Palau</option>
                                        <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                        <option value="Panama">Panama</option>
                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                        <option value="Paraguay">Paraguay</option>
                                        <option value="Peru">Peru</option>
                                        <option value="Philippines" selected>Philippines</option>
                                        <option value="Pitcairn">Pitcairn</option>
                                        <option value="Poland">Poland</option>
                                        <option value="Portugal">Portugal</option>
                                        <option value="Puerto Rico">Puerto Rico</option>
                                        <option value="Qatar">Qatar</option>
                                        <option value="Reunion">Reunion</option>
                                        <option value="Romania">Romania</option>
                                        <option value="Russian Federation">Russian Federation</option>
                                        <option value="Rwanda">Rwanda</option>
                                        <option value="Saint Helena">Saint Helena</option>
                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                        <option value="Saint Lucia">Saint Lucia</option>
                                        <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                        <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                        <option value="Samoa">Samoa</option>
                                        <option value="San Marino">San Marino</option>
                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Senegal">Senegal</option>
                                        <option value="Serbia">Serbia</option>
                                        <option value="Seychelles">Seychelles</option>
                                        <option value="Sierra Leone">Sierra Leone</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="Slovakia">Slovakia</option>
                                        <option value="Slovenia">Slovenia</option>
                                        <option value="Solomon Islands">Solomon Islands</option>
                                        <option value="Somalia">Somalia</option>
                                        <option value="South Africa">South Africa</option>
                                        <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                        <option value="Spain">Spain</option>
                                        <option value="Sri Lanka">Sri Lanka</option>
                                        <option value="Sudan">Sudan</option>
                                        <option value="Suriname">Suriname</option>
                                        <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                        <option value="Swaziland">Swaziland</option>
                                        <option value="Sweden">Sweden</option>
                                        <option value="Switzerland">Switzerland</option>
                                        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                        <option value="Taiwan">Taiwan</option>
                                        <option value="Tajikistan">Tajikistan</option>
                                        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                        <option value="Thailand">Thailand</option>
                                        <option value="Timor-leste">Timor-leste</option>
                                        <option value="Togo">Togo</option>
                                        <option value="Tokelau">Tokelau</option>
                                        <option value="Tonga">Tonga</option>
                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                        <option value="Tunisia">Tunisia</option>
                                        <option value="Turkey">Turkey</option>
                                        <option value="Turkmenistan">Turkmenistan</option>
                                        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                        <option value="Tuvalu">Tuvalu</option>
                                        <option value="Uganda">Uganda</option>
                                        <option value="Ukraine">Ukraine</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="United Kingdom">United Kingdom</option>
                                        <option value="United States">United States</option>
                                        <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                        <option value="Uruguay">Uruguay</option>
                                        <option value="Uzbekistan">Uzbekistan</option>
                                        <option value="Vanuatu">Vanuatu</option>
                                        <option value="Venezuela">Venezuela</option>
                                        <option value="Viet Nam">Viet Nam</option>
                                        <option value="Virgin Islands, British">Virgin Islands, British</option>
                                        <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                        <option value="Wallis and Futuna">Wallis and Futuna</option>
                                        <option value="Western Sahara">Western Sahara</option>
                                        <option value="Yemen">Yemen</option>
                                        <option value="Zambia">Zambia</option>
                                        <option value="Zimbabwe">Zimbabwe</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid-row">
                                <div class="mb-3">
                                    <label for="civil_status" class="form-label">Civil Status</label>
                                    <select id="civil_status" name="civil_status" class="form-select" required>
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <input type="text" id="religion" name="religion" class="form-control" required placeholder="Your religion">
                                </div>
                            </div>
                            <div class="grid-row">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select id="gender" name="gender" class="form-control" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="citizenship" class="form-label">Citizenship</label>
                                    <select name="citizenship" id="citizenship" class="form-control" required>
                                        <option value="">-- select one --</option>
                                        <option value="afghan">Afghan</option>
                                        <option value="albanian">Albanian</option>
                                        <option value="algerian">Algerian</option>
                                        <option value="american">American</option>
                                        <option value="andorran">Andorran</option>
                                        <option value="angolan">Angolan</option>
                                        <option value="antiguans">Antiguans</option>
                                        <option value="argentinean">Argentinean</option>
                                        <option value="armenian">Armenian</option>
                                        <option value="australian">Australian</option>
                                        <option value="austrian">Austrian</option>
                                        <option value="azerbaijani">Azerbaijani</option>
                                        <option value="bahamian">Bahamian</option>
                                        <option value="bahraini">Bahraini</option>
                                        <option value="bangladeshi">Bangladeshi</option>
                                        <option value="barbadian">Barbadian</option>
                                        <option value="barbudans">Barbudans</option>
                                        <option value="batswana">Batswana</option>
                                        <option value="belarusian">Belarusian</option>
                                        <option value="belgian">Belgian</option>
                                        <option value="belizean">Belizean</option>
                                        <option value="beninese">Beninese</option>
                                        <option value="bhutanese">Bhutanese</option>
                                        <option value="bolivian">Bolivian</option>
                                        <option value="bosnian">Bosnian</option>
                                        <option value="brazilian">Brazilian</option>
                                        <option value="british">British</option>
                                        <option value="bruneian">Bruneian</option>
                                        <option value="bulgarian">Bulgarian</option>
                                        <option value="burkinabe">Burkinabe</option>
                                        <option value="burmese">Burmese</option>
                                        <option value="burundian">Burundian</option>
                                        <option value="cambodian">Cambodian</option>
                                        <option value="cameroonian">Cameroonian</option>
                                        <option value="canadian">Canadian</option>
                                        <option value="cape verdean">Cape Verdean</option>
                                        <option value="central african">Central African</option>
                                        <option value="chadian">Chadian</option>
                                        <option value="chilean">Chilean</option>
                                        <option value="chinese">Chinese</option>
                                        <option value="colombian">Colombian</option>
                                        <option value="comoran">Comoran</option>
                                        <option value="congolese">Congolese</option>
                                        <option value="costa rican">Costa Rican</option>
                                        <option value="croatian">Croatian</option>
                                        <option value="cuban">Cuban</option>
                                        <option value="cypriot">Cypriot</option>
                                        <option value="czech">Czech</option>
                                        <option value="danish">Danish</option>
                                        <option value="djibouti">Djibouti</option>
                                        <option value="dominican">Dominican</option>
                                        <option value="dutch">Dutch</option>
                                        <option value="east timorese">East Timorese</option>
                                        <option value="ecuadorean">Ecuadorean</option>
                                        <option value="egyptian">Egyptian</option>
                                        <option value="emirian">Emirian</option>
                                        <option value="equatorial guinean">Equatorial Guinean</option>
                                        <option value="eritrean">Eritrean</option>
                                        <option value="estonian">Estonian</option>
                                        <option value="ethiopian">Ethiopian</option>
                                        <option value="fijian">Fijian</option>
                                        <option value="filipino" selected>Filipino</option>
                                        <option value="finnish">Finnish</option>
                                        <option value="french">French</option>
                                        <option value="gabonese">Gabonese</option>
                                        <option value="gambian">Gambian</option>
                                        <option value="georgian">Georgian</option>
                                        <option value="german">German</option>
                                        <option value="ghanaian">Ghanaian</option>
                                        <option value="greek">Greek</option>
                                        <option value="grenadian">Grenadian</option>
                                        <option value="guatemalan">Guatemalan</option>
                                        <option value="guinea-bissauan">Guinea-Bissauan</option>
                                        <option value="guinean">Guinean</option>
                                        <option value="guyanese">Guyanese</option>
                                        <option value="haitian">Haitian</option>
                                        <option value="herzegovinian">Herzegovinian</option>
                                        <option value="honduran">Honduran</option>
                                        <option value="hungarian">Hungarian</option>
                                        <option value="icelander">Icelander</option>
                                        <option value="indian">Indian</option>
                                        <option value="indonesian">Indonesian</option>
                                        <option value="iranian">Iranian</option>
                                        <option value="iraqi">Iraqi</option>
                                        <option value="irish">Irish</option>
                                        <option value="israeli">Israeli</option>
                                        <option value="italian">Italian</option>
                                        <option value="ivorian">Ivorian</option>
                                        <option value="jamaican">Jamaican</option>
                                        <option value="japanese">Japanese</option>
                                        <option value="jordanian">Jordanian</option>
                                        <option value="kazakhstani">Kazakhstani</option>
                                        <option value="kenyan">Kenyan</option>
                                        <option value="kittian and nevisian">Kittian and Nevisian</option>
                                        <option value="kuwaiti">Kuwaiti</option>
                                        <option value="kyrgyz">Kyrgyz</option>
                                        <option value="laotian">Laotian</option>
                                        <option value="latvian">Latvian</option>
                                        <option value="lebanese">Lebanese</option>
                                        <option value="liberian">Liberian</option>
                                        <option value="libyan">Libyan</option>
                                        <option value="liechtensteiner">Liechtensteiner</option>
                                        <option value="lithuanian">Lithuanian</option>
                                        <option value="luxembourger">Luxembourger</option>
                                        <option value="macedonian">Macedonian</option>
                                        <option value="malagasy">Malagasy</option>
                                        <option value="malawian">Malawian</option>
                                        <option value="malaysian">Malaysian</option>
                                        <option value="maldivan">Maldivan</option>
                                        <option value="malian">Malian</option>
                                        <option value="maltese">Maltese</option>
                                        <option value="marshallese">Marshallese</option>
                                        <option value="mauritanian">Mauritanian</option>
                                        <option value="mauritian">Mauritian</option>
                                        <option value="mexican">Mexican</option>
                                        <option value="micronesian">Micronesian</option>
                                        <option value="moldovan">Moldovan</option>
                                        <option value="monacan">Monacan</option>
                                        <option value="mongolian">Mongolian</option>
                                        <option value="moroccan">Moroccan</option>
                                        <option value="mosotho">Mosotho</option>
                                        <option value="motswana">Motswana</option>
                                        <option value="mozambican">Mozambican</option>
                                        <option value="namibian">Namibian</option>
                                        <option value="nauruan">Nauruan</option>
                                        <option value="nepalese">Nepalese</option>
                                        <option value="new zealander">New Zealander</option>
                                        <option value="ni-vanuatu">Ni-Vanuatu</option>
                                        <option value="nicaraguan">Nicaraguan</option>
                                        <option value="nigerien">Nigerien</option>
                                        <option value="north korean">North Korean</option>
                                        <option value="northern irish">Northern Irish</option>
                                        <option value="norwegian">Norwegian</option>
                                        <option value="omani">Omani</option>
                                        <option value="pakistani">Pakistani</option>
                                        <option value="palauan">Palauan</option>
                                        <option value="panamanian">Panamanian</option>
                                        <option value="papua new guinean">Papua New Guinean</option>
                                        <option value="paraguayan">Paraguayan</option>
                                        <option value="peruvian">Peruvian</option>
                                        <option value="polish">Polish</option>
                                        <option value="portuguese">Portuguese</option>
                                        <option value="qatari">Qatari</option>
                                        <option value="romanian">Romanian</option>
                                        <option value="russian">Russian</option>
                                        <option value="rwandan">Rwandan</option>
                                        <option value="saint lucian">Saint Lucian</option>
                                        <option value="salvadoran">Salvadoran</option>
                                        <option value="samoan">Samoan</option>
                                        <option value="san marinese">San Marinese</option>
                                        <option value="sao tomean">Sao Tomean</option>
                                        <option value="saudi">Saudi</option>
                                        <option value="scottish">Scottish</option>
                                        <option value="senegalese">Senegalese</option>
                                        <option value="serbian">Serbian</option>
                                        <option value="seychellois">Seychellois</option>
                                        <option value="sierra leonean">Sierra Leonean</option>
                                        <option value="singaporean">Singaporean</option>
                                        <option value="slovakian">Slovakian</option>
                                        <option value="slovenian">Slovenian</option>
                                        <option value="solomon islander">Solomon Islander</option>
                                        <option value="somali">Somali</option>
                                        <option value="south african">South African</option>
                                        <option value="south korean">South Korean</option>
                                        <option value="spanish">Spanish</option>
                                        <option value="sri lankan">Sri Lankan</option>
                                        <option value="sudanese">Sudanese</option>
                                        <option value="surinamer">Surinamer</option>
                                        <option value="swazi">Swazi</option>
                                        <option value="swedish">Swedish</option>
                                        <option value="swiss">Swiss</option>
                                        <option value="syrian">Syrian</option>
                                        <option value="taiwanese">Taiwanese</option>
                                        <option value="tajik">Tajik</option>
                                        <option value="tanzanian">Tanzanian</option>
                                        <option value="thai">Thai</option>
                                        <option value="togolese">Togolese</option>
                                        <option value="tongan">Tongan</option>
                                        <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
                                        <option value="tunisian">Tunisian</option>
                                        <option value="turkish">Turkish</option>
                                        <option value="tuvaluan">Tuvaluan</option>
                                        <option value="ugandan">Ugandan</option>
                                        <option value="ukrainian">Ukrainian</option>
                                        <option value="uruguayan">Uruguayan</option>
                                        <option value="uzbekistani">Uzbekistani</option>
                                        <option value="venezuelan">Venezuelan</option>
                                        <option value="vietnamese">Vietnamese</option>
                                        <option value="welsh">Welsh</option>
                                        <option value="yemenite">Yemenite</option>
                                        <option value="zambian">Zambian</option>
                                        <option value="zimbabwean">Zimbabwean</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- School Attended Section -->
                        <div class="tab-pane fade" id="school-info" role="tabpanel" aria-labelledby="school-info-tab">
                            <div class="mb-3">
                                <label for="college" class="form-label">College</label>
                                <input type="text" id="college" name="college" class="form-control" required placeholder="Your College">
                            </div>
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select name="course" id="course" class="form-control" required>
                                    <option value="">Select Course</option>
                                    <option value="BSIT">BSIT</option>
                                    <option value="BSCS">BSCS</option>
                                    <option value="BSCOE">BSCOE</option>
                                    <option value="BSET">BSET</option>
                                    <option value="BSA">BSA</option>
                                    <option value="BSBA">BSBA</option>
                                    <option value="BSMA">BSMA</option>
                                    <option value="BSHM">BSHM</option>
                                    <option value="BSTM">BSTM</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="year_level" class="form-label">Year Level</label>
                                <select id="year_level" name="year_level" class="form-control" required>
                                    <option value="">Select Year</option>
                                    <option value="1st Year">1st Year</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="schoolID" class="form-label">School ID</label>
                                <input type="text" id="schoolID" name="schoolID" class="form-control" required placeholder="Your School ID">
                            </div>
                            <div class="mb-3">
                                <label for="high_school" class="form-label">High School</label>
                                <input type="text" id="high_school" name="high_school" class="form-control" required placeholder="Your High School">
                            </div>
                            <div class="mb-3">
                                <label for="elementary" class="form-label">Elementary</label>
                                <input type="text" id="elementary" name="elementary" class="form-control" required placeholder="Your Elementary School">
                            </div>
                        </div>

                        <!-- Reasons for Joining -->
                        <div class="tab-pane fade" id="reasons-info" role="tabpanel" aria-labelledby="reasons-info-tab">
                            <div class="mb-3"><br>
                                <label for="reasons_for_joining" class="form-label">Reasons for Joining</label><br>
                                <textarea class="form-control" id="reasons_for_joining" name="reasons_for_joining" rows="4" required></textarea>
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

@section('scripts')
<script>
    document.getElementById('search-field').addEventListener('input', function() {
        var query = this.value;
        
        if(query.length > 2) { // Start searching after 3 characters
            fetch('/admin/search-applications?query=' + query)
                .then(response => response.json())
                .then(data => {
                    var resultsContainer = document.getElementById('search-results');
                    resultsContainer.innerHTML = ''; // Clear previous results
                    
                    if (data.length > 0) {
                        data.forEach(application => {
                            var listItem = document.createElement('a');
                            listItem.href = 'javascript:void(0)';
                            listItem.classList.add('list-group-item', 'list-group-item-action');
                            listItem.innerText = `${application.first_name} ${application.last_name}`;
                            
                            // Add event listener to handle click
                            listItem.addEventListener('click', function() {
                                showApplicantDetails(application);
                            });
                            
                            resultsContainer.appendChild(listItem);
                        });
                    } else {
                        resultsContainer.innerHTML = '<p>No applications found</p>';
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    });

    function showApplicantDetails(application) {
        document.getElementById('applicant-details-pending').style.display = 'none';
        document.getElementById('applicant-details-approved').style.display = 'none';
        document.getElementById('applicant-details-rejected').style.display = 'none';

        // Determine which container to show based on the status
        let targetContainer;
        console.log(application.status);
        if (application.status.toLowerCase() === 'pending') {
            targetContainer = document.getElementById('applicant-details-pending');
        } else if (application.status.toLowerCase() === 'approved') {
            targetContainer = document.getElementById('applicant-details-approved');
        } else if (application.status.toLowerCase() === 'rejected') {
            targetContainer = document.getElementById('applicant-details-rejected');
        }
        const birthdate = new Date(application.birthdate); // Convert the birthdate string to a Date object
        const today = new Date(); // Get the current date

        // Calculate the age
        let age = today.getFullYear() - birthdate.getFullYear();
        const monthDifference = today.getMonth() - birthdate.getMonth();
        const dayDifference = today.getDate() - birthdate.getDate();

        // Adjust age if the current date is before the birthdate this year
        if (monthDifference < 0 || (monthDifference === 0 && dayDifference < 0)) {
            age--;
        }
        const addressParts = [
            application.street_address,
            application.city,
            application.state,
            application.country,
            application.postal_code ? `- ${application.postal_code}` : '' // Add hyphen only if postal code exists
        ];
        
        // Filter out empty parts and join with commas
        const address = addressParts.filter(Boolean).join(', ');

        // Fill in the applicant details
        targetContainer.querySelector('#detail-name').textContent = application.first_name + " " + application.middle_name + " " + application.last_name;
        targetContainer.querySelector('#detail-age').textContent = age;
        targetContainer.querySelector('#detail-gender').textContent = application.gender;
        targetContainer.querySelector('#detail-phone_number').textContent = application.phone_number;
        targetContainer.querySelector('#detail-email_address').textContent = application.email_address;
        targetContainer.querySelector('#detail-address').textContent = address;
        targetContainer.querySelector('#detail-religion').textContent = application.religion;
        targetContainer.querySelector('#detail-citizenship').textContent = application.citizenship;
        targetContainer.querySelector('#detail-civil_status').textContent = application.civil_status;
        targetContainer.querySelector('#detail-college').textContent = application.college;
        targetContainer.querySelector('#detail-course').textContent = application.course;
        targetContainer.querySelector('#detail-year_level').textContent = application.year_level;
        targetContainer.querySelector('#detail-schoolID').textContent = application.schoolID;
        targetContainer.querySelector('#detail-high_school').textContent = application.high_school;
        targetContainer.querySelector('#detail-elementary').textContent = application.elementary;
        targetContainer.querySelector('#detail-reasons_for_joining').textContent = application.reasons_for_joining;

        // Display the correct container
        targetContainer.style.display = 'block';

        // Optionally scroll to the details section
        document.getElementById('applicant-details').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endsection
