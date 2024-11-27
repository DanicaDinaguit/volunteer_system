@extends('layouts.volunteer_app')

@section('title', 'Volunteer Calendar')
    
@section('content')
    <h2 style="text-align: center; margin-top: 20px; color: #D98641;">
        CALENDAR 
        <a href="{{ route('volunteer.joinedEvents') }}" class="joined-events-link" aria-label="My Joined Events">
            <i class="fas fa-arrow-right"></i> <!-- Using Font Awesome for a cleaner arrow icon -->
        </a>
    </h2>
    <!-- <a href="" class="btn btn-primary" style="margin-top: 20px; background: #2c3e50; border: none; padding: 10px 20px; text-decoration: none; color: white; border-radius: 5px;">
        View Joined Events
    </a> -->
    <div id="calendar-container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div id="alertPlaceholder"></div>
        <div id="calendar" class="bg-light p-3 rounded shadow mx-auto mt-2"></div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Initializing FullCalendar...');
            var calendarEl = document.getElementById('calendar');

            var events = @json($events);
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                editable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'multiMonthYear,dayGridMonth,listYear'
                },
                height: 'auto',
                contentHeight: 'auto',
                aspectRatio: 2.0,
                expandRows: true,
                handleWindowResize: true,
                windowResizeDelay: 100,
                events: events,
                windowResize: function(view) {
                    if (window.innerWidth < 768) {
                        calendar.changeView('timeGridDay');
                    } else {
                        calendar.changeView('dayGridMonth');
                    }
                },
                eventClassNames: function(arg) {
                    // Check if the event end date is in the past
                    var now = new Date();
                    if (arg.event.end && arg.event.end < now) {
                        return ['past-event']; // Apply 'past-event' class to past events
                    }
                    return [];
                },
                eventClick: function(info) {
                    var googleEventId = info.event.id; // Google Calendar event ID
                    console.log("Google Event ID:", googleEventId);

                    // Fetch the actual database ID based on Google event ID
                    fetch(`/admin/getEventIdByGoogleId/${googleEventId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.db_event_id) {
                                // Redirect to the Event Details page using the database event ID
                                window.location.href = '/volunteer/eventDetails/' + data.db_event_id;
                            } else {
                                console.error('Event not found in database.');
                                alert('Event not found.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                },
            });

            calendar.render();
        });
    </script>
@endsection

@section('styles')
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar-container {
            margin: 20px 0 0 0;
        }

        #calendar {   
            width: 80%;
            margin: 0 auto;
            margin-top: -30px;
            padding: 20px;    
            background-color: #f8f9fa; 
            border-radius: 10px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        .past-event {
            /* color: white; Gray out past events */
            /* background-color: gray; */
            font-style: italic; /* Optional: Italicize text */
            /*text-decoration: line-through;  Optional: Add strikethrough */
            /*opacity: 0.6;  Optional: Reduce opacity for a faded effect */
        }

        /* Extra Responsiveness */

        @media (max-width: 768px) {
            #calendar {
                width: 100%;
                padding: 10px;
            }
        }

        @media (max-width: 576px) {
            #calendar {
                width: 100%;
                padding: 10px;
            }
        }
        .joined-events-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #6f833f; 
            font-size: 1.8rem;
            transition: transform 0.3s ease, color 0.3s ease;
            position: relative;
        }

        .joined-events-link:hover {
            color: #56722e;
            transform: scale(1.1);
        }

        .joined-events-link i {
            margin-left: 5px;
        }

        .joined-events-link::after {
            content: "My Joined Events";
            position: absolute;
            top: 150%;
            left: 50%;
            transform: translateX(-50%);
            visibility: hidden;
            opacity: 0;
            background-color: #555;
            color: #fff;
            font-size: 0.9rem;
            border-radius: 5px;
            padding: 5px 10px;
            white-space: nowrap;
            z-index: 10;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .joined-events-link:hover::after {
            visibility: visible;
            opacity: 1;
        }
    </style>
@endsection