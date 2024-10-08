<!-- resources/views/your-view.blade.php -->
@extends('layouts.volunteer_app')

@section('title', 'Volunteer Notification')

@section('content')
<div id="notification" class="container py-4" style="text-align: center;">
  <div class="d-flex justify-content-between align-items-center mb-3">
      <h3>Notifications</h3>
      <!-- Search Container -->
      <div class="position-relative">
          <input type="text" id="search-field" class="form-control" placeholder="Search..." style="display:none; width: 250px;">
          <button id="search-icon" class="btn btn-outline-secondary">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none">
                  <path d="M21 19.31L15.13 13.43C16.09 12.15 16.5 10.56 16.5 9C16.5 4.86 13.14 1.5 9 1.5C4.86 1.5 1.5 4.86 1.5 9C1.5 13.14 4.86 16.5 9 16.5C10.56 16.5 12.15 16.09 13.43 15.13L19.31 21L21 19.31ZM9 14C5.96 14 3.5 11.54 3.5 9C3.5 6.46 5.96 4 9 4C12.04 4 14.5 6.46 14.5 9C14.5 11.54 12.04 14 9 14Z" fill="#000"/>
              </svg>
          </button>
      </div>
  </div>

  <!-- Notifications Container with Fixed Height and Scrolling -->
  <div class="notif-result" style="max-height: 800px; min-height: 30px; overflow-y: auto;">
      @foreach($notifications as $notification)
      <div class="col-md-12 mb-2 notif-item" data-notification="{{ strtolower($notification->body) }}">
          @if ($notification->url)
              <a href="{{ $notification->url }}" class="text-decoration-none text-dark">
          @endif
              <div class="card p-2 d-flex justify-content-between align-items-center shadow-sm" style="border-radius: 8px;">
                  <div class="d-flex align-items-center">
                      <img src="{{ asset('images/notif-icon.png') }}" alt="Notification Icon" class="mr-2" style="width: 40px; height: 40px;">
                      <p class="mb-0" style="font-size: 14px;">{{ $notification->body }}</p>
                      <small class="text-muted" style="font-size: 12px; margin-left: 20px;">{{ $notification->created_at->diffForHumans() }}</small>
                      <button class="btn btn-sm btn-outline-danger delete-notif ml-2" style="font-size: 12px;" data-id="{{ $notification->id }}">Delete</button>
                  </div>
                  <!-- <div class="ml-2">
                      
                  </div> -->
              </div>
          @if ($notification->url)
              </a>
          @endif
      </div>
      @endforeach
  </div>
</div>

<script>
    // Toggle search field display
    document.getElementById('search-icon').addEventListener('click', function() {
        const searchField = document.getElementById('search-field');
        searchField.style.display = searchField.style.display === 'block' ? 'none' : 'block';
        if (searchField.style.display === 'block') searchField.focus();
    });

    // Filter notifications based on search input
    document.getElementById('search-field').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const notifications = document.querySelectorAll('.notif-item');

        notifications.forEach(notification => {
            const notificationText = notification.getAttribute('data-notification');
            if (notificationText.includes(query)) {
                notification.style.display = 'flex'; // Show matched notification
            } else {
                notification.style.display = 'none'; // Hide non-matched notification
            }
        });
    });

    // Delete notification
    document.querySelectorAll('.delete-notif').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const notificationId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this notification?')) {
                fetch(`/admin/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove notification
                        this.closest('.card').remove();
                    } else {
                        alert('Error deleting notification');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
</script>
@endsection
