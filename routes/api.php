<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TicketController as ApiTicketController;
use App\Http\Controllers\Api\TransactionController as ApiTransactionController;
use App\Http\Controllers\Api\OrganizerController as ApiOrganizerController;

/*
|--------------------------------------------------------------------------
| API Health Check
|--------------------------------------------------------------------------
*/
Route::get('/ping', function () {
    return response()->json([
        'status' => true,
        'message' => 'API berjalan'
    ]);
});

/*
|--------------------------------------------------------------------------
| Event API
|--------------------------------------------------------------------------
*/
// Publicly accessible read-only endpoints
Route::name('api.')->group(function () {
    Route::apiResource('events', EventController::class)->only(['index','show']);

    // Additional API resources (public read)
    Route::apiResource('tickets', ApiTicketController::class)->only(['index','show']);
    Route::get('/organizers', [ApiOrganizerController::class, 'index']);
});

// Event lifecycle and transaction endpoints moved into auth group below
// (write/owner actions require authentication)



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// JWT endpoints
Route::post('/auth/login-jwt', [AuthController::class, 'login']);
Route::post('/auth/refresh', [\App\Http\Controllers\TokenController::class, 'refresh']);
Route::post('/auth/logout', [AuthController::class, 'logout']);

Route::name('api.')->middleware(['jwt_or_sanctum', \App\Http\Middleware\LogActivity::class])->group(function () {
    // Endpoint yang butuh autentikasi (will accept Sanctum or JWT)
    Route::post('/logout', [AuthController::class, 'logout']);
    // For JWT clients, logout can accept `refresh_token` in body to revoke it.

    // Profile management endpoints
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    // Protected endpoints for creating/updating resources
    Route::apiResource('tickets', ApiTicketController::class)->only(['store','update','destroy']);

    // Events write endpoints (organizer-only actions)
    Route::apiResource('events', EventController::class)->only(['store','update','destroy']);

    // Transactions should be accessible only to authenticated users
    Route::apiResource('transactions', ApiTransactionController::class);

    // Event lifecycle endpoints (organizer actions)
    Route::get('/my-events', [EventController::class, 'myEvents']);
    Route::post('/events/{event}/publish', [EventController::class, 'publish']);
    Route::post('/events/{event}/cancel', [EventController::class, 'cancel']);

    // Activity logs (authenticated only)
    Route::get('/logs', [\App\Http\Controllers\Api\LogController::class, 'index']);

    // Users (authenticated)
    Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
});
