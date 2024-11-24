@extends('layouts.admin_app')

@section('title', 'Event Dashboard')

@section('content')
<style>
    .pagination {
        display: flex;
        justify-content: center;
    }

    .pagination .page-item .page-link {
    color: #007bff; /* Default color */
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 3px 5px; /* Reduced padding */
    font-size: 0.75rem; /* Smaller font size */
    width: 28px; /* Fixed width */
    height: 28px; /* Fixed height */
    display: flex;
    align-items: center;
    justify-content: center;
}


    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination .page-item:hover .page-link {
        background-color: #e9ecef;
        color: #0056b3;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
    }
</style>



<div style="margin-top: 90px;">
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold">Event Dashboard</h2>
            <div class="d-flex">
                <!-- Trigger the Modal -->
                <button type="button" class="btn-create btn-sm" data-bs-toggle="modal" data-bs-target="#createEventModal">
                    <i class="fas fa-plus me-2"></i>Create New Event
                </button>

                <a href="{{ route('admin.beneficiaries') }}" class="btn text-white btn-sm me-1" style="background-color: #6f833f; margin-left: 10px;" title="Register Beneficiary">
                   <i></i>Register Beneficiaries
                </a>
            </div> 
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Collapsible Filters -->
            <button class="btn btn-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                <i class="fas fa-filter"></i> Toggle Filters
            </button>
            
            <!-- attendance summary button -->
            <button type="button" id="attendance-summary-btn" href="#" class="btn btn-outline-info btn-sm">
                <i class="fas fa-chart-bar"></i> View Attendance Summary
            </button>   
        </div>
        <!-- Filter Form -->
        <div class="collapse mt-3" id="filterSection">
                <form method="GET" action="{{ route('admin.event') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="etype" class="form-label">Event Type</label>
                        <select id="etype" name="etype" class="form-select form-select-sm">
                            <option value="">All Types</option>
                            <option value="Values Formation and Partnership">Values Formation and Partnership</option>
                            <option value="Skills/Livelihood Training">Skills/Livelihood Training</option>
                            <option value="Health and Environment">Health and Environment</option>
                            <option value="Education and Technology">Education and Technology</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="date_start" class="form-label">Date Start</label>
                        <input type="date" id="date_start" name="date_start" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label for="date_end" class="form-label">Date End</label>
                        <input type="date" id="date_end" name="date_end" class="form-control form-control-sm">
                    </div>

                    <div class="col-md-3">
                        <label for="volunteers" class="form-label">Volunteer Slots (Min)</label>
                        <input type="number" id="volunteers" name="volunteers" class="form-control form-control-sm" placeholder="Minimum slots">
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                    </div>
                </form>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Event Name</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Volunteers</th>
                        <th scope="col">Attendance</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end)->format('h:i A') }}</td>
                        <td>{{ $event->number_of_volunteers }}</td>
                        <td>
                        <a href="{{ route('admin.attendance.show', $event->id) }}" class="btn btn-primary btn-sm" title="View Attendance">
                            <i class="fas fa-eye"></i> View Attendance
                        </a>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.eventView', $event->id) }}" class="btn btn-info btn-sm me-1" title="View Event Participants">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.eventDetails', $event->id) }}" class="btn btn-warning btn-sm me-1" title="Edit Event">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Event">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No events found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $events->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- Create Event Modal -->
<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Create New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Create Event Form -->
                <form action="{{ route('admin.createEvent.submit') }}" method="POST" class="row g-3">
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
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Save Event
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function docReady(fn) {
        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function() {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });

            function onScanSuccess(decodedText, decodedResult) {
                if (decodedText !== lastResult) {
                    ++countResults;
                    lastResult = decodedText;
                    console.log(`Scan result = ${decodedText}`, decodedResult);
                    
                    // Sending the scanned data to AttendanceController using AJAX
                    $.ajax({
                        url: "{{ route('admin.attendance.scan') }}",  // Route to your controller method
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",  // CSRF token for security
                            qr_data: decodedText  // Sending the scanned data
                        },
                        success: function(response) {
                            // Display success message or handle response
                            resultContainer.textContent = response.message;
                            alert(response.message);
                        },
                        error: function(xhr) {
                            // Handle error
                            resultContainer.textContent = 'Error: ' + xhr.responseText;
                            alert(xhr.responseText);
                        }
                    });
                }
            }

        
        function onScanError(qrCodeError) {
            // Handle error if needed
        }
        
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    });

    document.getElementById('attendance-summary-btn').addEventListener('click', function(e) {
        e.preventDefault();
        
        let dateStart = document.getElementById('date_start').value;
        let dateEnd = document.getElementById('date_end').value;
        
        if (!dateStart || !dateEnd) {
            // Set default date ranges based on the semester
            let today = new Date();
            let year = today.getFullYear();
            if (today.getMonth() < 6) {
                // First semester (Jan-Jun)
                dateStart = year + '-01-01';
                dateEnd = year + '-06-30';
            } else {
                // Second semester (Aug-Dec)
                dateStart = year + '-08-01';
                dateEnd = year + '-12-31';
            }
        }

        // Redirect to the attendance summary page with date filters
        window.location.href = `{{ route('admin.attendanceSummary') }}?date_start=${dateStart}&date_end=${dateEnd}`;
    });
</script>
@endsection
