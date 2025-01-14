@extends('layouts.admin_app')

@section('title', 'Event Attendance')

@section('content')
<div class="container" style="margin-top: 30px;">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold" style="color: #d98641;">{{ $event->title }} - Attendance</h2>
        <a href="{{ route('admin.event', $event->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Event
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
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
                <!-- Button Group for Export and Scan -->
                <div class="btn-group">
                    <a href="{{ route('admin.attendanceForm', $event->id) }}" class="btn btn-info btn-sm text-white">
                        <i class="fas fa-download"></i> Export
                    </a>
                    <button type="button" class="btn text-white btn-sm" style="background-color: #6f833f;" data-bs-toggle="modal" data-bs-target="#scanModal">
                        <i class="fas fa-qrcode me-2"></i>Scan
                    </button>
                </div>
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
                <div class="btn-group">
                    <a href="{{ route('admin.beneficiaryAttendanceForm', $event->id) }}" class="btn btn-info btn-sm text-white">
                        <i class="fas fa-download"></i> Export
                    </a>
                    <button type="button" class="btn text-white btn-sm" style="background-color: #6f833f;" data-bs-toggle="modal" data-bs-target="#addBeneficiaryModal">
                        <i class="fas fa-plus"></i> Attendance
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle">
                    <thead class="table-light" style="background-color: #4b7768; color: #fff;">
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Purok</th>
                            <th>Date Attended</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beneficiariesAttendance as $index => $beneficiary)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{$beneficiary->beneficiary->id}}</td>
                            <td>{{ $beneficiary->beneficiary->first_name }} {{ $beneficiary->beneficiary->middle_name ?? '' }} {{ $beneficiary->beneficiary->last_name }}</td>
                            <td>{{ $beneficiary->beneficiary->purok }}</td>
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
                    <h5 class="modal-title fw-bold" id="addBeneficiaryModalLabel">Assign Beneficiary Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.addBeneficiaryAttendance', $event->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="searchBeneficiary" class="form-label">Search Beneficiary</label>
                            <input type="text" id="searchBeneficiary" class="form-control" placeholder="Type beneficiary's name or ID">
                            <div id="beneficiaryResults" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        <input type="hidden" name="beneficiaryID" id="selectedBeneficiaryID">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="addBeneficiaryButton" disabled>Add Attendance</button>
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
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
    <div id="scanMessageToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="scanMessageToastBody"></div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
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
                            displayToast('success', response.message);
                        },
                        error: function(xhr) {
                            // Handle error
                            const errorMessage = xhr.responseJSON?.message || 'An error occurred while processing the scan.';
                            displayToast('danger', errorMessage);
                        }
                    });
                }
            }
        // Toast function
        function displayToast(type, message) {
            const toastEl = document.getElementById('scanMessageToast');
            const toastBodyEl = document.getElementById('scanMessageToastBody');

            // Set toast styles based on type
            toastEl.classList.remove('text-bg-primary', 'text-bg-danger', 'text-bg-success');
            toastEl.classList.add(`text-bg-${type}`);

            // Set the message
            toastBodyEl.textContent = message;

            // Show the toast
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
        
        function onScanError(qrCodeError) {
            // Handle error if needed
        }
        
        html5QrcodeScanner.render(onScanSuccess, onScanError);
    });

    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("searchBeneficiary");
        const resultsContainer = document.getElementById("beneficiaryResults");
        const selectedBeneficiaryID = document.getElementById("selectedBeneficiaryID");
        const addButton = document.getElementById("addBeneficiaryButton");

        searchInput.addEventListener("input", function () {
            const query = searchInput.value.trim();

            if (query.length >= 2) {
                fetch(`/admin/search-beneficiary?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsContainer.innerHTML = "";
                        if (data.length > 0) {
                            data.forEach(beneficiary => {
                                const item = document.createElement("button");
                                item.classList.add("list-group-item", "list-group-item-action");
                                item.textContent = `${beneficiary.first_name} ${beneficiary.last_name} (${beneficiary.purok})`;
                                item.dataset.id = beneficiary.id;

                                item.addEventListener("click", () => {
                                    selectedBeneficiaryID.value = beneficiary.id;
                                    searchInput.value = `${beneficiary.first_name} ${beneficiary.last_name}`;
                                    resultsContainer.innerHTML = "";
                                    addButton.disabled = false;
                                });

                                resultsContainer.appendChild(item);
                            });
                        } else {
                            resultsContainer.innerHTML = '<div class="text-muted">No results found</div>';
                        }
                    })
                    .catch(error => console.error("Error fetching beneficiaries:", error));
            } else {
                resultsContainer.innerHTML = "";
            }
        });
    });
</script>
@endsection
