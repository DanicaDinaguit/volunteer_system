@extends('layouts.admin_app')

@section('title', 'Event Attendance')

@section('content')
<div class="container" style="margin-top: 70px;">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold" style="color: #d98641;">{{ $event->title }} - Attendance</h2>
        <a href="{{ route('admin.event', $event->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Event
        </a>
    </div>

    <!-- Event Details -->
    <div class="card border-0 shadow-sm rounded-3 mb-3 bg-light">
        <div class="card-body d-flex flex-wrap justify-content-between text-muted">
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</div>
            <div><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end)->format('h:i A') }}</div>
            <div><strong>Volunteers Needed:</strong> {{ $event->number_of_volunteers }}</div>
            <div><strong>Attendance:</strong> {{ $attendances->count() }}</div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold" style="color: #d98641;">Volunteer Attendance</h4>
                <a href="{{ route('admin.attendanceForm', $event->id) }}" class="btn btn-info btn-sm text-white">
                    <i class="fas fa-download"></i> Export
                </a>
                <!-- Scan Button -->
                <button type="button" class="btn text-white btn-sm ms-2" style="background-color: #6f833f;" data-bs-toggle="modal" data-bs-target="#scanModal">
                    <i class="fas fa-qrcode me-2"></i>Scan
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle">
                    <thead class="table-light" style="background-color: #4b7768; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Course</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $index => $attendance)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $attendance->full_name }}</td>
                            <td>{{ $attendance->course }}</td>
                            <td>{{ \Carbon\Carbon::parse($attendance->time_in)->format('h:i A') }}</td>
                            <td>
                                {{ $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('h:i A') : 'Not checked out' }}
                            </td>
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
    </div>

    <!-- Beneficiaries Attendance Section -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold" style="color: #d98641;">Beneficiaries Attendance</h4>
                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#addBeneficiaryModal">
                    <i class="fas fa-plus"></i> Add Beneficiary
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle">
                    <thead class="table-light" style="background-color: #4b7768; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Purok</th>
                            <th>Date Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beneficiariesAttendance as $index => $beneficiary)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $beneficiary->first_name }} {{ $beneficiary->middle_name ?? '' }} {{ $beneficiary->last_name }}</td>
                            <td>{{ $beneficiary->purok }}</td>
                            <td>{{ \Carbon\Carbon::parse($beneficiary->date_attended)->format('F j, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-muted">No beneficiaries have attended this event yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Beneficiary Attendance Modal -->
    <div class="modal fade" id="addBeneficiaryModal" tabindex="-1" aria-labelledby="addBeneficiaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addBeneficiaryModalLabel">Add Beneficiary Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.addBeneficiaryAttendance', $event->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md">
                                <input type="text" name="search" class="form-control" placeholder="Search beneficiary" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Attendance</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scan QR Code Modal -->
    <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scanModalLabel">Scan QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-reader-results"></div>
                </div>
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
</script>
@endsection
