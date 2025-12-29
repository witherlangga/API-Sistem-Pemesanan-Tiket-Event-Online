<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\EventWebController;

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('home');

Route::get('/events-test', function () {
    $events = Event::latest()->get();
    return view('events.index', compact('events'));
});

/*
|--------------------------------------------------------------------------
| Event Web (CRUD + Lifecycle)
|--------------------------------------------------------------------------
*/
Route::get('/events', [EventWebController::class, 'index']);

// Public event detail page
Route::get('/events/{event}', [EventWebController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Event Management (organizers only)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventWebController::class, 'create']);
    Route::post('/events', [EventWebController::class, 'store']);
    Route::get('/events/{event}/edit', [EventWebController::class, 'edit']);
    Route::put('/events/{event}', [EventWebController::class, 'update']);
    Route::delete('/events/{event}', [EventWebController::class, 'destroy']);

    // lifecycle event
    Route::post('/events/{event}/publish', [EventWebController::class, 'publish']);
    Route::post('/events/{event}/cancel', [EventWebController::class, 'cancel']);
});

// Web auth & dashboards
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Auth pages
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);

Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);

Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// Dashboard & Profile
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
