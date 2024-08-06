@extends('layouts.public_app')

@section('title', 'Public Calendar of Events')
    
@section('content')
    <div id="gallery" style="margin-top: 130px; margin-left: 100px; margin-right: 100px;">
        <h1>Gallery of Events</h1>
        <div class="search-bar">
            <img src="Images/search-icon.png" class="search-icon" alt="Search Icon">
            <input type="text" class="search-input" placeholder="Search Year">
        </div>
    </div>
@endsection       
        