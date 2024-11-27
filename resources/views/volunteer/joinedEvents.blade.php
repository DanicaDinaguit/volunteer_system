@extends('layouts.volunteer_app')

@section('title', 'My Events')

@section('content')
<div class="container" style="margin-top: 20px">
    <h2 class="mb-2 text-center" style="color: #d98641;">
        <a href="{{ route('volunteer.calendar') }}" class="back-to-calendar-link" aria-label="Back to Calendar">
            <i class="fas fa-arrow-left"></i> <!-- Back arrow icon -->
        </a>
        My Events
    </h2>

    @if($events->isEmpty())
        <div class="alert alert-warning text-center py-3">
            <strong>Oops!</strong> You haven't participated in any events yet.
        </div>
    @else
        <!-- Sorting and Pagination Control -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Showing {{ $events->firstItem() }} - {{ $events->lastItem() }} of {{ $events->total() }} events</span>
            <form method="GET" class="form-inline">
                <label for="sort" class="mr-2 text-muted">Sort by:</label>
                <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Most Recent</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </form>
        </div>

        <!-- Event List -->
        <div class="row">
            @foreach($events as $event)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                    <img src="{{ asset('images/'.strtolower($event->category).'.png') }}" 
                             class="card-img-top" 
                             alt="{{ $event->category }} Image" 
                             style="object-fit: cover; height: 180px;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title" style="color: #d98641;">{{ $event->title }}</h5>
                            @if($event->event_date >= \Carbon\Carbon::now()->subMonth())
                                <span class="badge badge-success mb-2">New</span>
                            @endif
                            <p class="card-text text-muted mb-2">{{ \Str::limit($event->description, 70) }}</p>
                            <ul class="list-unstyled mb-3">
                                <li><i class="fas fa-calendar-alt"></i> <strong>Type:</strong> {{ $event->category }}</li>
                                <li><i class="fas fa-calendar-day"></i> <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</li>
                                <li><i class="fas fa-clock"></i> <strong>Time:</strong> {{ $event->start }} - {{ $event->end }}</li>
                                <li><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> {{ $event->event_location }}</li>
                                <li><i class="fas fa-calendar-day"></i> <strong>Joined Date:</strong> {{ \Carbon\Carbon::parse($event->joined_date)->format('F j, Y') }}</li>
                                
                            </ul>
                            <a href="{{ route('volunteer.eventDetails', $event->id) }}" class="btn-view" >
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $events->appends(request()->input())->links() }}
        </div>
    @endif
</div>
@endsection

@section('styles')
    <style>
        /* Custom Styles for Event Cards */
        .container h2 {
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 16px;
            background-color: #fff;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            padding: 20px;
        }

        .btn-view {
            color: white;
            background-color: #d98641;
            border:  1px solid #d98641 !important;
            text-align: center;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #d77c1b;
        }

        .badge-success {
            background-color: brown;
            color: #fff;
            border-radius: 30px !important;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
        }

        .list-unstyled li {
            font-size: 0.9rem;
            color: #777;
        }

        .d-flex justify-content-between {
            margin-bottom: 15px;
        }

        .pagination {
            display: flex;
            justify-content: center;
        }

        .pagination .page-link {
            border-radius: 50%;
            padding: 8px 12px;
            font-size: 1rem;
        }

        .pagination .page-link:hover {
            background-color: #007bff;
            color: white;
        }

        /* Card Image Styling */
        .card-img-top {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        /* Pagination Style */
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        /* Back Arrow Link Styling to link back to Calendar */
        .back-to-calendar-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            color: #6f833f; /* Custom arrow color */
            font-size: 1.8rem;
            transition: transform 0.3s ease, color 0.3s ease;
            position: relative;
        }

        .back-to-calendar-link:hover {
            color: #56722e; /* Slightly darker shade for hover effect */
            transform: scale(1.1);
        }
        .back-to-calendar-link::after {
            content: "My Calendar";
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
        .back-to-calendar-link:hover::after {
            visibility: visible;
            opacity: 1;
        }
        .back-to-calendar-link i {
            margin-right: 5px; /* Adds space between the arrow and text */
        }

        .container h2 {
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
        }
    </style>
@endsection
