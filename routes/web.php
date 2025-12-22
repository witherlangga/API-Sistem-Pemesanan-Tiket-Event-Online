<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\EventWebController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/events-test', function () {
    $events = Event::latest()->get();
    return view('events.index', compact('events'));
});



Route::get('/events', [EventWebController::class, 'index']);
Route::get('/events/create', [EventWebController::class, 'create']);
Route::post('/events', [EventWebController::class, 'store']);
Route::get('/events/{event}/edit', [EventWebController::class, 'edit']);
Route::put('/events/{event}', [EventWebController::class, 'update']);
Route::delete('/events/{event}', [EventWebController::class, 'destroy']);
