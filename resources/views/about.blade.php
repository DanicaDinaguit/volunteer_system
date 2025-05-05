@extends('layouts.public_app')

@section('title', 'SOCI - About')
    
@section('content')
    <div class="main-about" 
        style="background: url('{{ asset('images/carousel-images/carousel-img2.png') }}') no-repeat center center/cover;">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 my-3 d-flex flex-column justify-content-center align-items-center">
                    <img class="px-5 h-auto rounded-4" src="{{ asset('images/aboutLOGO.png')}}" alt="SOCI LOGO" width="300px" height="300px">
                </div>
                <div class="col-12 my-1">
                    <p class="mx-5 h-auto rounded-4 d-flex flex-column fs-6">
                        The Social Orientation and Community Involvement (SOCI) is a department that manages, coordinates, and supervises all activities related to Social Orientation and Community Involvement of Asian College-Dumaguete.  SOCI  aims to provide acceptable, affordable, attainable, and sustainable community projects and programs. It creates transparency and practices integrity in all aspects and processes of the programs and community projects it implements. The department operates within the allotted operational budget approved by the administration on the implementation of community projects. 
                    </p>
                </div>
                <div class="col-12 my-1">
                    <p class="mx-5 h-auto rounded-4 d-flex flex-column fs-6">  
                        SOCI commits to partner with the local community, specifically with the four Puroks of Barangay Taclobo, namely: Banikanhon, Santan, Valtimar, and Ladrico II.  It creates and conducts initial and final community surveys/assessments of implemented projects and it seeks to ensure that the community will be empowered within the next five years. 
                    </p>
                </div>
                <div class="col-12 my-1">
                    <p class="mx-5 h-auto rounded-4 d-flex flex-column fs-6">
                        SOCI fosters active participation in community activities, growth, and development; wherein it serves as an instrument of social and cultural transmission of change. It helps the  school develop in its faculty, staff, students, and alumni a social conscience through awareness, concern, and involvement in community development. It refuses to abide in any form of  compulsion and coercion among its volunteers and partners, as it ensures the safety and security of all volunteers at all times.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- MISSION AND VISION -->
    <div class="mission-vision" 
        style="
        background: url('{{ asset('images/carousel-images/carousel-img2.png') }}') no-repeat center center/cover;">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 col-lg-6 my-4 px-lg-5">
                    <div class="mv-box mx-5 px-5 py-4 h-auto rounded-4 d-flex flex-column justify-content-center animate-on-scroll zoom-in">
                        <h2 class="text-center pb-3">Our Mission</h2>
                        <p><i class="bi bi-quote"></i>To be an agent of community transformation by fostering relevant programs through volunteerism and strong community engagement.<i class="bi bi-quote"></i></p>
                    </div>
                </div>
                <div class="col-12 col-lg-6 my-4 px-lg-5">
                    <div class="mv-box mx-5 px-5 py-4 h-auto rounded-4 d-flex flex-column justify-content-center animate-on-scroll zoom-in">
                        <h2 class="text-center pb-3">Our Vision</h2>
                        <p><i class="bi bi-quote"></i>To provide sustainable programs in order to respond to the present and future needs of the community.<i class="bi bi-quote"></i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sub-about">
        <img class="w-100 h-auto" src="{{ asset('images/about-images/about-art.svg') }}">
        <div class="container sub-about-content pb-4">
            <div class="row">
                <!-- <div class="col-12 my-2 d-flex justify-content-center align-items-cente">
                    <h2 class="fw-bold">Board Directors</h2>
                </div> -->
                <div class="col-12 my-2 d-flex justify-content-center align-items-center">
                    <img class="img-fluid w-100 h-auto" src="{{ asset('images/about-images/org-chart.svg') }}" alt="Board Directors">
                </div>
            </div>
        </div>
    </div>
@endsection
        