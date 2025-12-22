<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;


Route::get('/ping', function () {
    return response()->json([
        'status' => true,
        'message' => 'API berjalan'
    ]);
});

Route::apiResource('events', EventController::class);
