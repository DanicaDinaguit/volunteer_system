@extends('layouts.admin_app')

@section('title', 'Admin Gallery of Events')
    
@section('content')
<!-- <script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script> -->
    <div id="gallery-bg" class="container-fluid py-4">
        <div class="gallery container">
            <h1 class="text-center fw-bold mb-4">Gallery of Events</h1>
            <div style="display: flex; flex-direction: row; gap: 50px; justify-content: center;">
                <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fphoto.php%3Ffbid%3D122109809246597533%26set%3Da.122109810062597533%26type%3D3&show_text=false&width=500" width="500" height="663" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fphoto.php%3Ffbid%3D122109810350597533%26set%3Da.122109810920597533%26type%3D3&show_text=false&width=500" width="500" height="663" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
            </div>
        </div>
    </div>
@endsection       
        