<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;

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
Route::apiResource('events', EventController::class);

Route::get('/my-events', [EventController::class, 'myEvents']);
Route::post('/events/{event}/publish', [EventController::class, 'publish']);
Route::post('/events/{event}/cancel', [EventController::class, 'cancel']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Endpoint yang butuh autentikasi (akan ditambah di poin 6-7)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile management endpoints
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
});
