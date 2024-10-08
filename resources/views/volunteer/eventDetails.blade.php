@extends('layouts.volunteer_app')

@section('title', 'Event Details')

@section('content')
<div style="margin-top: 115px;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h3 class="card-title mb-0">{{ $event->title }}</h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-calendar-alt"></i> 
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }} 
                                    from {{ \Carbon\Carbon::parse($event->start)->format('g:i A') }} 
                                    to {{ \Carbon\Carbon::parse($event->end)->format('h:i A') }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="text-primary"><strong>Event Details</strong></h5>
                            <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> {{ $event->event_location }}</p>
                            <p><i class="fas fa-tags"></i> <strong>Category:</strong> {{ $event->category }}</p>
                            <p class="mt-3"><strong>Description:</strong></p>
                            <p>{{ $event->description }}</p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                            <div class="text-muted">
                                <strong>Volunteers Needed:</strong> 
                                <span id="volunteers-needed" class="badge bg-success">
                                    {{ $event->number_of_volunteers - $event->volunteers_joined }}
                                </span> 
                                out of {{ $event->number_of_volunteers }}
                            </div>
                            <div>
                                <button class="btn btn-outline-primary btn-lg px-4" id="join-btn" 
                                    {{ $event->volunteers_joined >= $event->number_of_volunteers ? 'disabled' : '' }}
                                    {{ $hasJoined ? 'disabled' : '' }}>
                                    {{ $hasJoined ? 'Joined' : 'Join' }}
                                </button>
                            </div>
                        </div>

                        <!-- QR Code Display Section -->
                        <div class="mt-4" id="qr-code-section" style="display: none;">
                            <h5 class="text-primary"><strong>This is your QR Code for attendance purposes.</strong></h5>
                            <img id="qr-code-image" src="" alt="QR Code" class="img-fluid">
                            <br>
                            <!-- Download QR Code Button -->
                            <a id="download-qr-code" href="#" download="qr_code.png" class="btn btn-outline-success mt-3">
                                Download QR Code
                            </a>
                        </div>

                        <div class="mt-4 text-muted">
                            <small>Event Status: <strong>{{ ucfirst($event->event_status) }}</strong></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card {
        border-radius: 12px;
    }
    .card-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .btn-outline-primary {
        border: 2px solid #007bff;
        color: #007bff;
        font-weight: 600;
    }
    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }
</style>

<script>
    document.getElementById('join-btn').addEventListener('click', function() {
        const joinButton = this;
        const volunteersNeededElement = document.getElementById('volunteers-needed');
        const qrCodeSection = document.getElementById('qr-code-section');
        const qrCodeImage = document.getElementById('qr-code-image');
        const downloadQrCodeLink = document.getElementById('download-qr-code');
        const id = "{{ $event->id }}";

        if (confirm('Are you sure you want to join this event?')) {
            fetch(`/volunteer/eventDetails/${id}/join`, {  // Adjust route if necessary
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })  // Pass the eventID or any other necessary data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the volunteers needed count
                    volunteersNeededElement.textContent = data.remaining_volunteers;

                    // Disable the join button if no more volunteers are needed
                    if (data.remaining_volunteers == 0) {
                        joinButton.disabled = true;
                        joinButton.textContent = "Event Full";
                    } else {
                        joinButton.disabled = true;
                        joinButton.textContent = "Joined";
                    }

                    // Show the QR code
                    qrCodeSection.style.display = 'block';
                    qrCodeImage.src = data.qr_code; // Set the QR code image URL

                    // Set the download link to the QR code image URL
                    downloadQrCodeLink.href = data.qr_code;  // Ensure the QR code can be downloaded
                } else {
                    alert('Error joining event: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
</script>
@endsection
