@extends('layouts.admin_app')

@section('title', 'Admin Gallery of Events')
    
@section('content')
    <div id="gallery-bg" class="container-fluid py-4">
        <div class="gallery container">
            <h1 class="text-center fw-bold mb-4">Gallery of Events</h1>
            <div class="iframe-container">
                <div class="iframe-item">
                    <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fphoto.php%3Ffbid%3D122109809246597533%26set%3Da.122109810062597533%26type%3D3&show_text=false&width=500" 
                        width="500" 
                        height="663" 
                        style="border:none;overflow:hidden" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                </div>
                <div class="iframe-item">
                    <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fphoto.php%3Ffbid%3D122109810350597533%26set%3Da.122109810920597533%26type%3D3&show_text=false&width=500" 
                        width="500" 
                        height="663" 
                        style="border:none;overflow:hidden" 
                        scrolling="no" 
                        frameborder="0" 
                        allowfullscreen="true" 
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Base styling for the iframe container */
        .iframe-container {
            display: flex;
            flex-direction: row;
            gap: 50px;
            justify-content: center;
            flex-wrap: wrap; /* Allow items to wrap on smaller screens */
        }

        /* Ensure iframe items don't exceed the container width */
        .iframe-item {
            flex: 1 1 500px; /* Allow each iframe to grow/shrink; 500px is the ideal width */
            max-width: 500px;
        }

        /* Responsive styling for small screens */
        @media (max-width: 768px) {
            .iframe-container {
                flex-direction: column;
                gap: 20px;
            }

            .iframe-item {
                max-width: 100%;
            }

            .iframe-item iframe {
                width: 100%;
                height: 100%;
            }
        }
    </style>
@endsection
