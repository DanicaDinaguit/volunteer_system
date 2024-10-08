@extends('layouts.admin_app')

@section('title', 'Admin Calendar')

@section('content')
    <div id="calendar-container">
        <h1 class="text-center">Calendar</h1>

        <!-- Schedule Event Button -->
        <!-- <div class="d-flex justify-content-between align-items-center mb-3 mt-4" style="width: 90%; margin: 0 auto;">
            <button id="scheduleEventButton" class="btn btn-primary">
                <i class="fas fa-calendar-plus"></i> Schedule Event +
            </button>
        </div> -->

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
                            <div class="col-12 col-md-6">
                                <label for="title" class="form-label">Event Name</label>
                                <input type="text" id="title" name="name" class="form-control" placeholder="Event Name" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="event-type" class="form-label">Event Type</label>
                                <select id="event-type" name="type" class="form-select" required>
                                    <option value="Values and Education">Values and Education</option>
                                    <option value="Partnership and Development">Partnership and Development</option>
                                    <option value="Environment and Health">Environment and Health</option>
                                    <option value="Social Awareness and Concern">Social Awareness and Concern</option>
                                    <option value="Skills and Livelihood">Skills and Livelihood</option>
                                    <option value="Other Events">Other Events</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="event-description" class="form-label">Event Description</label>
                                <textarea id="event-description" name="description" class="form-control" rows="5" placeholder="Event Description" required></textarea>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="start-time" class="form-label">Start Time</label>
                                <input type="datetime-local" id="start-time" name="start_time" class="form-control" required>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="end-time" class="form-label">End Time</label>
                                <input type="datetime-local" id="end-time" name="end_time" class="form-control" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="event-location" class="form-label">Event Location</label>
                                <input type="text" id="event-location" name="location" class="form-control" placeholder="Event Location" required>
                            </div>

                            <div class="col-12 col-md-6 col-lg-3">
                                <label for="volunteers-needed" class="form-label">Number of Volunteers</label>
                                <input type="number" id="volunteers-needed" name="volunteers_needed" class="form-control" placeholder="Number of Volunteers" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="partner" class="form-label">Partner/s</label>
                                <input type="text" id="partner" name="epartner" class="form-control" placeholder="Partner/s" required>
                            </div>

                            <div class="col-12">
                                <button type="submit" id="createEventButton" class="btn btn-success">
                                    <i class="fas fa-save"></i> Save Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Event Details Modal -->
        <!-- <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Title:</strong> <span id="modalTitle"></span></p>
                        <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                        <p><strong>Start:</strong> <span id="modalStart"></span></p>
                        <p><strong>End:</strong> <span id="modalEnd"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="deleteEventButton" class="btn btn-danger">Delete Event</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Initializing FullCalendar...');
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                editable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
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
                // events: {!! json_encode($events) !!},
                events: '/admin/events/',
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
                    document.getElementById('start-time').value = info.startStr;
                    document.getElementById('end-time').value = info.endStr;
                },
                eventClick: function(info) {
                    var event = info.event;

                    var eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
                    document.getElementById('modalTitle').innerText = event.title;
                    document.getElementById('modalDescription').innerText = event.extendedProps.description || 'No description';
                    document.getElementById('modalStart').innerText = event.start.toISOString();
                    document.getElementById('modalEnd').innerText = event.end ? event.end.toISOString() : 'Not specified';
                    eventModal.show();

                    document.getElementById('deleteEventButton').onclick = function() {
                        if (confirm('Are you sure you want to delete this event?')) {
                            fetch('/admin/events/' + event.id, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            }).then(response => response.json()).then(data => {
                                if (data.success) {
                                    event.remove();
                                    eventModal.hide();
                                }
                            });
                        }
                    };
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
                    document.getElementById('start-time').value = info.dateStr + 'T00:00';
                    document.getElementById('end-time').value = info.dateStr + 'T23:59';
                },
                // events: function(fetchInfo, successCallback, failureCallback) {
                //     fetch('/admin/events')
                //         .then(response => response.json())
                //         .then(data => {
                //             console.log(data);
                //             successCallback(data);
                //         })
                //         .catch(failureCallback);
                // }
            });

            calendar.render();

            document.getElementById('createEventModal').addEventListener('hidden.bs.modal', function () {
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                document.body.style.overflow = '';
                document.body.style.marginRight = '';
                document.body.style.paddingRight = '';
            });

            // document.getElementById('scheduleEventButton').onclick = function () {
            //     var createModal = new bootstrap.Modal(document.getElementById('createEventModal'));
            //     document.getElementById('event-form').reset();
            //     createModal.show();
            // };

            document.getElementById('createEventButton').onclick = function () {
                var form = document.getElementById('event-form');
                const formData = new FormData(form);
                let eventData = {};
                formData.forEach((value, key) => eventData[key] = value);

                var startTime = new Date(formData.get('start_time'));
                var endTime = new Date(formData.get('end_time'));
                if (endTime <= startTime) {
                    alert('End time must be after start time.');
                    return;
                }

                fetch('{{ route('admin.createEvent') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        
                        calendar.addEvent({
                            id: data.event.id,
                            title: formData.get('name'),
                            start: formData.get('start_time'),
                            end: formData.get('end_time'),
                            description: formData.get('description'),
                            extendedProps: {
                                location: formData.get('location'),
                                volunteers_needed: formData.get('volunteers_needed'),
                                epartner: formData.get('epartner'),
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
            overflow: hidden;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar-container {
            margin: 100px 0 0 0;
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
