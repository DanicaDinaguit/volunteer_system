<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\GoogleCalendarController;


// Public routes
Route::get('/', [GoogleCalendarController::class, 'fetchEvents'])->name('index');

Route::get('/about', function () { return view('about'); })->name('about'); 

Route::get('/calendar', function () { return view('calendar'); })->name('calendar');

Route::get('/gallery', function () { return view('gallery'); })->name('gallery');

Route::get('/application', function () { return view('application'); })->name('application');

Route::post('/application', [ApplicationController::class, 'submitApplication'])->name('application.submit');


// Admin Routes (Protected with admin.auth middleware)
Route::prefix('admin')->group(function () {
    // Admin Home Page
    Route::get('/Home', [AdminController::class, 'Home'])->name('admin.Home');

    // Admin Calendar Page
    Route::get('/events', [GoogleCalendarController::class, 'fetchEvents']);
    Route::get('/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');
    Route::post('/calendar/create', [AdminController::class, 'storeEvent'])->name('admin.createEvent');
    Route::resource('/events', EventController::class);
    

    // Route::get('/calendar', [GoogleCalendarController::class, 'listEvents']);
    // Route::get('/auth/google', [GoogleCalendarController::class, 'redirectToGoogle']);
    // Route::get('/callback', [GoogleCalendarController::class, 'handleGoogleCallback']);
    // Route::post('/calendar/create', [GoogleCalendarController::class, 'createEvent']);

    // Admin Create New Event Page
    Route::get('/event', [EventController::class, 'eventDashboard'])->name('admin.event');
    // Route::get('/createEvent', [EventController::class, 'viewCreateEvent'])->name('admin.createEvent');
    // Route::post('/createEvent', [EventController::class, 'store'])->name('admin.createEvent.submit');

    // Admin View Application Page
    Route::get('/viewApplication', [AdminController::class, 'viewApplications'])->name('admin.viewApplication');
    Route::get('/viewApplication/{memberApplicationID}', [ApplicationController::class, 'getApplicantDetails'])
    ->name('admin.getApplicantDetails');

    // Admin Messages Page
    Route::get('/messages', [MessageController::class, 'index'])->name('admin.messages');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('admin.show');
    Route::get('/search-users', [MessageController::class, 'searchUsers'])->name('admin.searchUsers');

    Route::post('/approve-application/{id}', [ApplicationController::class, 'approveApplication'])->name('admin.approveApplication');
    Route::post('/reject-application/{id}', [ApplicationController::class, 'rejectApplication'])->name('admin.rejectApplication');
    
    Route::get('/notification', [AdminController::class, 'notifications'])->name('admin.notification');
    Route::delete('/notifications/{id}', [AdminController::class, 'destroy'])->name('notifications.destroy');

    // Admin Gallery Page
    Route::get('/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');

    // Admin Create Certification Page
    Route::get('/createCertification', [AdminController::class, 'createCertification'])->name('admin.createCertification');

    // Admin Profile Page
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

// Admin Sign In
Route::get('admin/signIn', [AdminController::class, 'showSignInForm'])->name('admin.signIn');
Route::post('admin/signIn', [AdminController::class, 'signIn'])->name('admin.signIn.submit');


// Volunteer Routes (Protected with volunteer.auth middleware)
Route::prefix('volunteer')->group(function () {
    // Volunteer Home Page
    Route::get('/volunteer/Home', [MemberController::class, 'Home'])->name('volunteer.Home');

    // Volunteer Calendar
    Route::get('/volunteer/calendar', [MemberController::class, 'calendar'])->name('volunteer.calendar');

    // Volunteer Messages
    Route::get('/volunteer/messages', [MemberController::class, 'messages'])->name('volunteer.messages');

    // Volunteer Notification
    Route::get('/volunteer/notification', [MemberController::class, 'notification'])->name('volunteer.notification');

    // Volunteer Gallery
    Route::get('volunteer/gallery', [MemberController::class, 'gallery'])->name('volunteer.gallery');

    Route::get('volunteer/notification', function () { return view('volunteer/notification'); })->name('volunteer.notification');

    // Volunteer Profile Page
    Route::get('/volunteer/profile', [MemberController::class, 'profile'])->name('volunteer.profile');
    Route::post('/volunteer/profile/update', [MemberController::class, 'updateProfile'])->name('volunteer.updateProfile');

    // Volunteer Logout
    Route::post('/volunteer/logout', [MemberController::class, 'logout'])->name('volunteer.logout');

    // Volunteer About Us Page
    Route::get('volunteer/about', function () { return view('volunteer/about'); })->name('volunteer.about');
});

// Volunteer Sign Up Page
Route::get('/volunteer/signUp', [MemberController::class, 'showSignUpForm'])->name('volunteer.signup');
Route::post('/volunteer/signUp', [MemberController::class, 'store'])->name('volunteer.signup.store');

// Volunteer Sign In Page
Route::get('/volunteer/signIn', [MemberController::class, 'showSignInForm'])->name('volunteer.signIn');
Route::post('/volunteer/signIn', [MemberController::class, 'signIn'])->name('volunteer.signIn.submit');

// Auth Routes (This includes routes for login, register, and password resets)
// Auth::routes();

// Google Calendar API-Integration Routes
// Route::get('/auth/google', [GoogleCalendarController::class, 'redirectToGoogle']);
// Route::get('/callback', [GoogleCalendarController::class, 'handleGoogleCallback']);
// Route::post('/calendar/create', [GoogleCalendarController::class, 'createEvent']);


