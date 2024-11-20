@extends('layouts.public_app')

@section('title', 'Public Calendar of Events')
    
@section('content')
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
                                window.location.href = '/eventDetails/' + data.db_event_id;
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
    </style>
@endsection