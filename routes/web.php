<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Http\Controllers\EventWebController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-tester', function () {
    return view('api-tester');
});
Route::get('/', function () {
    return view('welcome');
});

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
Route::get('/events/create', [EventWebController::class, 'create']);
Route::post('/events', [EventWebController::class, 'store']);
Route::get('/events/{event}/edit', [EventWebController::class, 'edit']);
Route::put('/events/{event}', [EventWebController::class, 'update']);
Route::delete('/events/{event}', [EventWebController::class, 'destroy']);

// lifecycle event
Route::post('/events/{event}/publish', [EventWebController::class, 'publish']);
Route::post('/events/{event}/cancel', [EventWebController::class, 'cancel']);
