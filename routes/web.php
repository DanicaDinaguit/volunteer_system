<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


// Public routes
Route::get('/', [EventController::class, 'index'])->name('index');

Route::get('/about', function () { return view('about'); })->name('about'); 

Route::get('/calendar', function () { return view('calendar'); })->name('calendar');

Route::get('/gallery', function () { return view('gallery'); })->name('gallery');

Route::get('/application', function () { return view('application'); })->name('application');

Route::post('/application', [ApplicationController::class, 'submitApplication'])->name('application.submit');



// Admin Routes (Protected with admin.auth middleware)
Route::prefix('admin')->group(function () {
    // Admin Home Page
    Route::get('/admin/Home', [AdminController::class, 'Home'])->name('admin.Home');

    // Admin Calendar Page
    Route::get('/admin/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');

    // Admin Create New Event Page
    Route::get('/admin/createEvent', [EventController::class, 'viewCreateEvent'])->name('admin.createEvent');
    Route::post('/admin/createEvent', [EventController::class, 'store'])->name('admin.createEvent.submit');

    // Admin View Application Page
    Route::get('/admin/viewApplication', [AdminController::class, 'viewApplications'])->name('admin.viewApplication');

    // Admin Messages Page
    Route::get('/admin/messages', [AdminController::class, 'messages'])->name('admin.messages');

    // Admin Gallery Page
    Route::get('/admin/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');

    // Admin Create Certification Page
    Route::get('/admin/createCertification', [AdminController::class, 'createCertification'])->name('admin.createCertification');

    // Admin Profile Page
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
});


// Admin Sign In
Route::get('admin/signin', [AdminController::class, 'showSignInForm'])->name('admin.signin');
Route::post('admin/signin', [AdminController::class, 'signIn'])->name('admin.signin.submit');



// Volunteer Routes (Protected with volunteer.auth middleware)
Route::prefix('volunteer')->group(function () {
    // Volunteer Home Page
    Route::get('/volunteer/Home', [MemberController::class, 'Home'])->name('volunteer.Home');

    // Volunteer Gallery
    Route::get('volunteer/gallery', [MemberController::class, 'gallery'])->name('volunteer.gallery');

    // Volunteer Profile Page
    Route::get('/volunteer/profile', [MemberController::class, 'profile'])->name('volunteer.profile');
    Route::post('/volunteer/profile/update', [MemberController::class, 'updateProfile'])->name('volunteer.updateProfile');

    // Volunteer Logout
    Route::post('/volunteer/logout', [MemberController::class, 'logout'])->name('volunteer.logout');

    // Volunteer Gallery Page
    Route::get('volunteer/gallery', function () { return view('volunteer/gallery'); })->name('volunteer.gallery');
    
    // Volunteer About Us Page
    Route::get('volunteer/about', function () { return view('volunteer/about'); })->name('volunteer.about');

    //Volunteer Notification Page
    Route::get('volunteer/notification', function () { return view('volunteer/notification'); })->name('volunteer.notification');
});




// Volunteer Sign Up Page
Route::get('/volunteer/signUp', [MemberController::class, 'showSignUpForm'])->name('volunteer.signup');
Route::post('/volunteer/signUp', [MemberController::class, 'store'])->name('volunteer.signup.store');

// Volunteer Sign In Page
Route::get('/volunteer/signIn', [MemberController::class, 'showSignInForm'])->name('volunteer.signIn');
Route::post('/volunteer/signIn', [MemberController::class, 'signIn'])->name('volunteer.signIn.submit');

// Auth Routes (This includes routes for login, register, and password resets)
Auth::routes();


