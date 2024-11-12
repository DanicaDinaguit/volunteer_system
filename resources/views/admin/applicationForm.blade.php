<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form for SOCI Student Volunteer</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h2 {
            text-align: center;
        }
        label {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .signature {
            margin-top: 50px;
        }
        table, td{
            border: 1px solid black;
            text-align: center;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 5px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td><img src="{{ public_path('images/ACLOGO.png') }}" style="height: 50px; width: 60px;"  alt="Asian College Logo"></td>
            <td>
                <strong>ASIAN COLLEGE OF SCIENCE AND TECHNOLOGY, FOUNDATION INC.</strong><br>
                Dr. V. Locsin Street, Brgy. Taclobo, Dumaguete City
            </td>
            <td></td>
        </tr>
        <tr>
            <td>Department</td>
            <td><strong>SOCIAL ORIENTATION AND COMMUNITY INVOLVEMENT</strong></td>
            <td><img src="{{ public_path('images/LOGO1.png') }}" style="height: 50px; width: 50px;"></td>
        </tr>
    </table><br>
    <h2>Application Form for SOCI Student Volunteer</h2>

    <section>
        <h3>Personal Information</h3>
        <div class="form-group">
            <label>Name: </label>
            <span>{{ $applicant->name }}</span>
        </div>
        <div class="form-group">
            <label>Address: </label>
            <span>{{ $applicant->address }}</span>
        </div>
        <div class="form-group">
            <label>Mobile Number: </label>
            <span>{{ $applicant->mobile_number }}</span>
        </div>
        <div class="form-group">
            <label>Email Address: </label>
            <span>{{ $applicant->email }}</span>
        </div>
        <div class="form-group">
            <label>Age: </label>
            <span>{{ $applicant->age }}</span>
            <label>Gender: </label>
            <span>{{ $applicant->gender }}</span>
        </div>
        <div class="form-group">
            <label>Religion: </label>
            <span>{{ $applicant->religion }}</span>
            <label>Civil Status: </label>
            <span>{{ $applicant->civil_status }}</span>
        </div>
        <div class="form-group">
            <label>Citizenship: </label>
            <span>{{ $applicant->citizenship }}</span>
        </div>
    </section>

    <section>
        <h3>School Attended</h3>
        <div class="form-group">
            <label>College: </label>
            <span>{{ $applicant->college }}</span>
        </div>
        <div class="form-group">
            <label>Course: </label>
            <span>{{ $applicant->course }}</span>
        </div>
        <div class="form-group">
            <label>Year Level: </label>
            <span>{{ $applicant->year_level }}</span>
        </div>
        <div class="form-group">
            <label>High School: </label>
            <span>{{ $applicant->high_school }}</span>
        </div>
        <div class="form-group">
            <label>Elementary: </label>
            <span>{{ $applicant->elementary }}</span>
        </div>
    </section>

    <section>
        <h3>Reasons for joining as SOCI Volunteer</h3>
        <div class="form-group">
            <span>{{ $applicant->reasons_for_joining }}</span>
        </div>
    </section>

    <section class="signature">
        <p>Approved by:</p>
        <p>___________________________________________________<br>
        Signature over printed name of Department Chairperson</p>
        <p>____________________________________________________<br>
        Signature over printed name of SOCI Program Coordinator</p>
        <p>____________________________________________________<br>
        Signature over printed name of SOCI Director</p>
    </section>
</body>
</html>
