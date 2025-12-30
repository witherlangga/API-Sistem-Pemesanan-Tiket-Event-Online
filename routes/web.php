<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\EventWebController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TransactionController;

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
Route::get('/events', [EventWebController::class, 'index'])->name('events.index');

/*
|--------------------------------------------------------------------------
| Event Management (organizers only) - MUST BE BEFORE {event} ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/events/create', [EventWebController::class, 'create'])->name('events.create');
    Route::post('/events', [EventWebController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventWebController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventWebController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventWebController::class, 'destroy'])->name('events.destroy');

    // lifecycle event
    Route::post('/events/{event}/publish', [EventWebController::class, 'publish'])->name('events.publish');
    Route::post('/events/{event}/cancel', [EventWebController::class, 'cancel'])->name('events.cancel');
});

// Public event detail page - MUST BE AFTER SPECIFIC ROUTES
Route::get('/events/{event}', [EventWebController::class, 'show'])->name('events.show');

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

    /*
    |--------------------------------------------------------------------------
    | Ticket Booking Routes (for customers)
    |--------------------------------------------------------------------------
    */
    Route::get('/events/{event}/book', [TicketController::class, 'book'])->name('tickets.book');
    Route::post('/events/{event}/book', [TicketController::class, 'processBooking'])->name('tickets.process-booking');

    /*
    |--------------------------------------------------------------------------
    | Ticket Management Routes (for organizers)
    |--------------------------------------------------------------------------
    */
    Route::get('/events/{event}/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/events/{event}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/events/{event}/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/events/{event}/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/events/{event}/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/events/{event}/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    /*
    |--------------------------------------------------------------------------
    | Transaction Routes (for customers)
    |--------------------------------------------------------------------------
    */
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/payment', [TransactionController::class, 'payment'])->name('transactions.payment');
    Route::post('/transactions/{transaction}/upload-proof', [TransactionController::class, 'uploadProof'])->name('transactions.upload-proof');
    Route::put('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

    /*
    |--------------------------------------------------------------------------
    | Transaction Management Routes (for organizers)
    |--------------------------------------------------------------------------
    */
    Route::get('/events/{event}/transactions', [TransactionController::class, 'eventTransactions'])->name('events.transactions');
    Route::put('/transactions/{transaction}/verify', [TransactionController::class, 'verify'])->name('transactions.verify');
});
