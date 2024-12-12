@extends('layouts.admin_app')

@section('title', 'Admin Calendar')

@section('content')
    <div id="calendar-container">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div id="alertPlaceholder"></div>
        <div id="calendar" class="bg-light p-3 rounded shadow mx-auto mt-2"></div>

        <!-- Create New Event Modal -->
        <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Create Event Form -->
                        <form id="event-form" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label for="ename" class="form-label">Event Name</label>
                                <input type="text" id="ename" name="ename" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="etype" class="form-label">Event Type</label>
                                <select id="etype" name="etype" class="form-select" required>
                                    <option value="">Select Event Type</option>
                                    <option value="Values and Education">Values and Education</option>
                                    <option value="Partnership and Development">Partnership and Development</option>
                                    <option value="Environment and Health">Environment and Health</option>
                                    <option value="Social Awareness and Concern">Social Awareness and Concern</option>
                                    <option value="Skills and Livelihood">Skills and Livelihood</option>
                                    <option value="Other Events">Other Events</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="edesc" class="form-label">Description</label>
                                <textarea id="edesc" name="edesc" rows="5" class="form-control" required></textarea>
                            </div>

                            <div class="col-md-3">
                                <label for="slots" class="form-label">Volunteer Slots</label>
                                <input type="number" id="slots" name="slots" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label for="edate" class="form-label">Date</label>
                                <input type="date" id="edate" name="edate" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label for="timeStart" class="form-label">Time Start</label>
                                <input type="time" id="timeStart" name="timeStart" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label for="timeEnd" class="form-label">Time End</label>
                                <input type="time" id="timeEnd" name="timeEnd" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="elocation" class="form-label">Location</label>
                                <input type="text" id="elocation" name="elocation" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="epartner" class="form-label">Partner/s</label>
                                <select id="epartner" name="epartner" class="form-select" required>
                                    <option value="">Select a Partner</option>
                                    @foreach($partners as $partner)
                                        <option value="{{ $partner->partner_name }}">{{ $partner->partner_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Submit Button -->
                            <div class="col-md-12">
                                <button type="submit" id="createEventButton" class="btn btn-success">
                                    <i class="fas fa-save"></i> Save Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                customButtons: {
                    addEventButton: {
                        text: 'Add Event',
                        click: function () {
                            var createModal = new bootstrap.Modal(document.getElementById('createEventModal'));
                            document.getElementById('event-form').reset();
                            createModal.show();
                        }
                    }
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
                select: function(info) {
                    var createModal = new bootstrap.Modal(document.getElementById('createEventModal'));
                    createModal.show();

                    document.getElementById('event-form').reset();
                    document.getElementById('timeStart').value = info.startStr;
                    document.getElementById('timeEnd').value = info.endStr;
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
                                window.location.href = '/admin/eventDetails/' + data.db_event_id;
                            } else {
                                console.error('Event not found in database.');
                                alert('Event not found.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                },
                eventDrop: function(info) {
                    var event = info.event;
                    fetch('/admin/events/' + event.id, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            start: event.start.toISOString(),
                            end: event.end ? event.end.toISOString() : event.start.toISOString(),
                        })
                    }).then(response => response.json()).then(data => {
                        if (!data.success) {
                            info.revert();
                        }
                    });
                },
                eventResize: function(info) {
                    var event = info.event;
                    fetch('/admin/events/' + event.id, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            start: event.start.toISOString(),
                            end: event.end.toISOString(),
                        })
                    }).then(response => response.json()).then(data => {
                        if (!data.success) {
                            info.revert();
                        }
                    });
                },
                dateClick: function(info) {
                    var createModal = new bootstrap.Modal(document.getElementById('createEventModal'));
                    createModal.show();

                    document.getElementById('event-form').reset();
                    document.getElementById('edate').value = info.dateStr + 'T00:00';
                    document.getElementById('timeStart').value = info.dateStr + 'T00:00';
                    document.getElementById('timeEnd').value = info.dateStr + 'T23:59';
                },
            });

            calendar.render();

            document.getElementById('createEventModal').addEventListener('hidden.bs.modal', function () {
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                document.body.style.overflow = '';
                document.body.style.marginRight = '';
                document.body.style.paddingRight = '';
            });

            document.getElementById('createEventButton').onclick = function () {
                event.preventDefault();

                var form = document.getElementById('event-form');
                const formData = new FormData(form);
                let eventData = {};
                formData.forEach((value, key) => eventData[key] = value);

                // Ensure start and end times are valid
                let startTime = new Date(formData.get('edate') + ' ' + formData.get('timeStart'));
                let endTime = new Date(formData.get('edate') + ' ' + formData.get('timeEnd'));
                if (endTime <= startTime) {
                    alert('End time must be after start time.');
                    return;
                }

                fetch('{{ route('admin.createEventCalendar') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Custom-Header': 'JavaScriptRequest'
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        
                        calendar.addEvent({
                            id: data.event.id,
                            title: data.event.title,
                            start: new Date(data.event.event_date + ' ' + data.event.start).toISOString(),
                            end: new Date(data.event.event_date + ' ' + data.event.end).toISOString(),
                            description: data.event.description,
                            extendedProps: {
                                location: data.event.event_location,
                                volunteers_needed: data.event.number_of_volunteers,
                                partner: formData.get('epartner')  // Assuming 'epartner' is stored separately
                            }
                        });
                        
                        var createModal = bootstrap.Modal.getInstance(document.getElementById('createEventModal'));
                        if (createModal) {
                            createModal.hide();
                        }
                        
                        showAlert('Event Created Successfully!', 'success');
                    } else {
                        showAlert('Failed to create event. Please try again.', 'danger');
                    }
                }).catch(error => {
                    showAlert('An error occurred. Please try again later.', 'danger');
                    console.error('Error:', error);
                });
            };

            function showAlert(message, type) {
                var alertPlaceholder = document.getElementById('alertPlaceholder');
                var alertHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                alertPlaceholder.innerHTML = alertHTML;

                setTimeout(function () {
                    var alert = bootstrap.Alert.getInstance(document.querySelector('.alert'));
                    if (alert) {
                        alert.close();
                    }
                }, 3000);
            };

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
            margin: 40px 0 0 0;
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
