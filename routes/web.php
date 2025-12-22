<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/events-test', function () {
    $events = Event::latest()->get();
    return view('events.index', compact('events'));
});
