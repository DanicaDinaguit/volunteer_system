<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficiaries Event Attendance</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            margin-left: 20px;
            margin-right: 20px;
            margin-top: 0;
            color: #333;
            font-size: 12px;
        }

        h2 {
            color: #866e5d;
            font-weight: bold;
        }

        .container {
            width: 100%;
        }
        #header-section.th,  #header-section.td{
            border: none;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #f8f8f8;
            padding: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .event-detail {
            color: #6f833f;
            font-weight: bold;
            margin: 5px 0;
        }

        h4 {
            font-weight: bold;
        }
        .school_address{
            font-size: 8px;
            color: blue;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: center;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .text-muted {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div id="header-section">
            <table style="width: 100%; margin-bottom: 20px; border: none;">
                <tr>
                    <td style="width: 10%; vertical-align: top; horizontal-align: left; border: none;">
                        <img src="{{ public_path('images/AsianCollege.jpg') }}" alt="Asian College Logo" style="height: 60px; width: 250px; display: inline-block;">
                    </td>
                    <td style="width: 70%; text-align: left; vertical-align: top; border: none;">
                        <h4 class="school_address" style="margin-left: 220px;">
                            ASIAN COLLEGE OF SCIENCE<br>
                            AND TECHNOLOGY FOUNDATION.<br>
                            INC. DUMAGUETE CITY<br>
                            Dr. V. Locsin St.<br>
                            Dumaguete City 6200<br>
                            (035) 225-4714<br>
                            (035) 225-4804<br>
                            www.asiancollege.edu.ph
                        </h4>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Attendance Table -->
        <div class="card">
            <h4>Asian College Dumaguete Campus <br>
                Social Orientation and Community Involvement (SOCI)<br>
                Beneficiary {{$event->title}} Attendance <br>
                {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}, {{ \Carbon\Carbon::parse($event->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end)->format('h:i A') }}</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Purok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->first_name }} {{ $attendance->middle_name }} {{ $attendance->last_name }}</td>
                        <td>{{ $attendance->purok }}</td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted">No attendees yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
