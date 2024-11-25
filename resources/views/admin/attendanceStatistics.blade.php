<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Attendance</title>
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
                Attendance Statistics<br>
                {{ \Carbon\Carbon::parse($dateStart)->format('F j, Y') }}, {{ \Carbon\Carbon::parse($dateEnd)->format('F j, Y') }}</h4>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Volunteer</th>
                        <th colspan="{{ count($events) }}" class="text-center">Event Type</th> <!-- Add a heading for event types -->
                        <th class="text-center">Total</th>
                        <th class="text-center">Certificate</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        @foreach($events as $category => $categoryEvents)
                            <th class="text-center">{{ $category }}</th> <!-- Display category names under "Event Type" -->
                        @endforeach
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendanceData as $index => $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data['volunteer_full_name'] ?? 'Volunteer information missing' }}</td>
                            @foreach($events as $category => $categoryEvents)
                                @php
                                    Log::info('Attendance Data', [
                                        'attendance' => $data['attendance']->toArray()
                                    ]);

                                    // Calculate attendance for this volunteer in this category
                                    $eventsInCategory = $categoryEvents->pluck('id')->toArray();  // Get event IDs as an array

                                    // Filter attendance based on events in the current category
                                    $attendanceInCategory = $data['attendance']->filter(function ($attendance) use ($eventsInCategory) {
                                        // Access the event ID through the nested participant relationship
                                        $eventId = $attendance->participant->event->id;

                                        // Check if the event ID is in the list of event IDs for the current category
                                        return in_array($eventId, $eventsInCategory);
                                    });

                                    // Log information about the category, event IDs, and attendance count
                                    Log::info('Processing category attendance', [
                                        'category' => $category,
                                        'events_in_category' => $eventsInCategory,
                                        'attendance_in_category_count' => $attendanceInCategory->count(),  // Log the count of filtered attendance
                                    ]);
                                @endphp
                                <td class="text-center">{{ $attendanceInCategory->count() }}</td>  
                            @endforeach

                            <td class="text-center">{{ $data['total_events_attended'] }}</td>
                            <td class="text-center">{{ $data['certificate_tier'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

