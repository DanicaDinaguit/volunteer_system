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
Route::get('/about', function () {
    return view('about');
})->name('about'); 

Route::get('/calendar', function () {
    return view('calendar');
})->name('calendar');

Route::get('/gallery', function () {
    return view('gallery');
})->name('gallery');

// Public View Application Page
Route::get('/application', function () {
    return view('application');
})->name('application');

Route::post('/application', [ApplicationController::class, 'submitApplication'])->name('application.submit');

// Admin Routes (Protected with admin.auth middleware)
Route::middleware('admin')->group(function () {
    // Admin Home Page
    Route::get('/admin/Home', [AdminController::class, 'Home'])->name('admin.Home');

    // Admin Create New Event page
    Route::post('/admin/createEvent', [EventController::class, 'store'])->name('admin.createEvent');

    // Admin Profile Page
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.updateProfile');
});
// Route::get('admin/viewApplication', function () { return view('admin/viewApplication'); })->name('admin.viewApplication');
Route::get('/admin/viewApplication', [AdminController::class, 'viewApplications'])->name('admin.viewApplication');
// Admin Sign In
Route::get('admin/signin', [AdminController::class, 'showSignInForm'])->name('admin.signin');
Route::post('admin/signin', [AdminController::class, 'signIn'])->name('admin.signin.submit');

// Volunteer Routes (Protected with volunteer.auth middleware)
Route::middleware('volunteer')->group(function () {
    // Volunteer Home Page
    Route::get('/volunteer/Home', [MemberController::class, 'Home'])->name('volunteer.Home');

    // Volunteer Profile Page
    Route::get('/volunteer/profile', [MemberController::class, 'profile'])->name('volunteer.profile');
    Route::post('/volunteer/profile/update', [MemberController::class, 'updateProfile'])->name('volunteer.updateProfile');
    Route::post('/volunteer/logout', [MemberController::class, 'logout'])->name('volunteer.logout');

    // Volunteer About Us Page
    Route::get('volunteer/about', function () { return view('volunteer/about'); })->name('volunteer.about');
});
Route::get('volunteer/notification', function () { return view('volunteer/notification'); })->name('volunteer.notification');

// Volunteer Sign Up Page
Route::get('/volunteer/signUp', [MemberController::class, 'showSignUpForm'])->name('volunteer.signup');
Route::post('/volunteer/signUp', [MemberController::class, 'store'])->name('volunteer.signup.store');

// Volunteer Sign In Page
Route::get('/volunteer/signIn', [MemberController::class, 'showSignInForm'])->name('volunteer.signIn');
Route::post('/volunteer/signIn', [MemberController::class, 'signIn'])->name('volunteer.signIn.submit');

// Auth Routes (This includes routes for login, register, and password resets)
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
