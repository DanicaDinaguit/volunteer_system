<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\GoogleCalendarController;


// Public routes
Route::get('/', [EventController::class, 'index'])->name('index');

Route::get('/about', function () { return view('about'); })->name('about'); 

//Calendar
Route::get('/events', [GoogleCalendarController::class, 'fetchEvents']);
Route::get('/calendar', [AdminController::class, 'calendar'])->name('calendar');
Route::get('/getEventIdByGoogleId/{google_event_id}', [EventController::class, 'getEventIdByGoogleId']);
Route::get('/eventDetails/{id}', [EventController::class, 'showEventDetails'])->name('eventDetails');

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
    Route::post('/calendar/create', [EventController::class, 'storeEvent'])->name('admin.createEventCalendar');
    Route::resource('/events', EventController::class);
    Route::get('/getEventIdByGoogleId/{google_event_id}', [EventController::class, 'getEventIdByGoogleId']);

    // Admin Create New Event Page
    Route::get('/createEvent', [EventController::class, 'viewCreateEvent'])->name('admin.createEvent');
    Route::get('/event', [EventController::class, 'eventDashboard'])->name('admin.event');
    Route::put('/event/{event}', [EventController::class, 'update'])->name('admin.events.update');
    Route::delete('/event/{event}', [EventController::class, 'destroy'])->name('admin.events.destroy');
    Route::post('/createEvent', [EventController::class, 'storeEvent'])->name('admin.createEvent.submit');
    Route::get('/eventDetails/{id}', [EventController::class, 'showAdmin'])->name('admin.eventDetails');
    Route::get('/eventView/{id}', [EventController::class, 'showEventParticipants'])->name('admin.eventView');
    Route::get('/search-beneficiary', [BeneficiaryController::class, 'searchBeneficiary'])->name('admin.searchBeneficiary');
    Route::post('/add-beneficiary-attendance/{id}', [BeneficiaryController::class, 'addBeneficiaryAttendance'])->name('admin.addBeneficiaryAttendance');
    
    Route::get('/volunteerAttendance/{volunteerId}', [AttendanceController::class, 'getVolunteerAttendance'])->name('admin.volunteerAttendance');
    Route::post('/attendance/scan', [AttendanceController::class, 'scan'])->name('admin.attendance.scan');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('admin.attendance.show');
    Route::get('/attendanceSummary', [AttendanceController::class, 'attendanceSummary'])->name('admin.attendanceSummary');
    Route::get('/attendanceForm/{id}', [AttendanceController::class, 'download'])->name('admin.attendanceForm');
    Route::get('/attendanceStatistics', [AttendanceController::class, 'downloadStatistics'])->name('admin.attendanceStatistics');
    Route::get('/beneficiaryAttendanceForm/{id}', [BeneficiaryController::class, 'download'])->name('admin.beneficiaryAttendanceForm');
    
    //For beneficiary
    Route::get('/createBeneficiary', [BeneficiaryController::class, 'create'])->name('admin.createBeneficiary');
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->name('admin.beneficiaries.store');
    Route::get('/beneficiaryList', [BeneficiaryController::class, 'downloadListBeneficiary'])->name('admin.beneficiaryList');


    // Admin View Application Page
    Route::get('/viewApplication', [AdminController::class, 'viewApplications'])->name('admin.viewApplication');
    Route::get('/viewApplication/{memberApplicationID}', [ApplicationController::class, 'getApplicantDetails'])
    ->name('admin.getApplicantDetails');
    Route::get('/applicationForm', [ApplicationController::class, 'formApplication'])->name('admin.applicationForm');
    Route::get('/applicationForm/{memberApplicationID}', [ApplicationController::class, 'download'])->name('admin.download');
    Route::get('/search-applications', [ApplicationController::class, 'search'])->name('applications.search');
    
    // Admin Messages Page
    // Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::get('/messages', [MessageController::class, 'index'])->name('admin.messages');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.store');
    
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('admin.show');
    Route::get('/search-users', [MessageController::class, 'searchUsers'])->name('admin.searchUsers');
    // Route::put('/messages/{id}', [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{id}', [MessageController::class, 'destroy'])->name('admin.destroy');
   
    Route::post('/approve-application/{id}', [ApplicationController::class, 'approveApplication'])->name('admin.approveApplication');
    Route::post('/reject-application/{id}', [ApplicationController::class, 'rejectApplication'])->name('admin.rejectApplication');
    
    Route::get('/notification', [AdminController::class, 'notifications'])->name('admin.notification');
    Route::delete('/notifications/{id}', [AdminController::class, 'destroy'])->name('notifications.destroy');

    
    // Admin Gallery Page
    Route::get('/gallery', [AdminController::class, 'gallery'])->name('admin.gallery');

    // Volunteer About Us Page
    Route::get('/about', function () { return view('admin/about'); })->name('admin.about');
    // Admin Profile Page
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});


// Admin Sign In
Route::get('admin/signin', [AdminController::class, 'showSignInForm'])->name('admin.signin');
Route::post('admin/signin', [AdminController::class, 'signIn'])->name('admin.signin.submit');



// Volunteer Routes (Protected with volunteer.auth middleware)
Route::prefix('volunteer')->group(function () {
    // Volunteer Home Page
    Route::get('/Home', [MemberController::class, 'Home'])->name('volunteer.Home');

    // Admin Calendar Page
    Route::get('/events', [GoogleCalendarController::class, 'fetchEvents']);
    Route::get('/calendar', [AdminController::class, 'calendar'])->name('volunteer.calendar');
    Route::get('/getEventIdByGoogleId/{google_event_id}', [EventController::class, 'getEventIdByGoogleId']);

    Route::get('/eventDetails/{id}', [EventController::class, 'showEventDetails'])->name('volunteer.eventDetails');
    Route::post('/eventDetails/{id}/join', [EventController::class, 'join'])->name('volunteer.eventDetails.join');
    Route::get('/joinedEvents', [EventController::class, 'volunteerEvents'])->name('volunteer.joinedEvents');
    // Add this route in your web.php
    Route::post('/eventDetails/regenerate-qr/{id}', [EventController::class, 'regenerateQrCode'])->name('volunteer.regenerateQr');

    // Volunteer Gallery
    Route::get('/gallery', [MemberController::class, 'gallery'])->name('volunteer.gallery');

    Route::get('/messages', [MessageController::class, 'index'])->name('volunteer.messages');
    Route::post('/messages/store', [MessageController::class, 'store'])->name('messages.stores');

    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('volunteer.show');
    Route::get('/search-users', [MessageController::class, 'searchUsers'])->name('volunteer.searchUsers');

    // Volunteer Profile Page
    Route::get('/profile', [MemberController::class, 'profile'])->name('volunteer.profile');
    Route::post('/profile/update', [MemberController::class, 'updateProfile'])->name('volunteer.updateProfile');

    // Volunteer Logout
    Route::post('/logout', [MemberController::class, 'logout'])->name('volunteer.logout');

    // Volunteer Gallery Page
    Route::get('/gallery', function () { return view('volunteer/gallery'); })->name('volunteer.gallery');
    
    // Volunteer About Us Page
    Route::get('/about', function () { return view('volunteer/about'); })->name('volunteer.about');

    //Volunteer Notification Page
    Route::get('/notification', [MemberController::class, 'notifications'])->name('volunteer.notification');
});




// Volunteer Sign Up Page
Route::get('/volunteer/signUp', [MemberController::class, 'showSignUpForm'])->name('volunteer.signup');
Route::post('/volunteer/signUp', [MemberController::class, 'store'])->name('volunteer.signup.store');

// Volunteer Sign In Page
Route::get('/volunteer/signIn', [MemberController::class, 'showSignInForm'])->name('volunteer.signin');
Route::post('/volunteer/signIn', [MemberController::class, 'signIn'])->name('volunteer.signIn.submit');

// Auth Routes (This includes routes for login, register, and password resets)
Auth::routes();


