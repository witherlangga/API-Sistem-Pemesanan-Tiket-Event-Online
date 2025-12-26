<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;

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
