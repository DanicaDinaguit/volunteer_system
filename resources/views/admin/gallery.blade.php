@extends('layouts.admin_app')

@section('title', 'Admin Gallery of Events')
    
@section('content')
<div id="gallery-bg" class="container-fluid py-5" style="">
        <div class="gallery container">
            <h1 class="text-center fw-bold mb-4">Gallery of Events</h1>
            
            <!-- Search Bar -->
            <div class="d-flex justify-content-center mb-4">
                <div class="search-bar d-flex align-items-center" style="position: relative; max-width: 400px; width: 100%;">
                    <!-- <img src="Images/search-icon.png" class="search-icon" alt="Search Icon" style="position: absolute; left: 10px; width: 20px;"> -->
                    <input type="text" id="searchYear" class="form-control ps-5" placeholder="Search by Year or Date" style="border-radius: 30px;">
                </div>
            </div>
            
            <!-- Gallery Images -->
            <div id="gallery-results">
                <div id="gallery-images" class="row g-4 justify-content-center">
                    <!-- Sample Gallery Item -->
                    <!-- Use a loop to populate images dynamically from the Facebook API -->
                </div>
            </div>
        </div>
    </div>
@endsection       
        