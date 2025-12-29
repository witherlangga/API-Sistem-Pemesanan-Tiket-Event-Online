<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // If guest, show the customer dashboard as the public/home landing
        if (! $user) {
            // Provide a lightweight guest object for the view
            $guest = (object) ['name' => 'Guest'];
            // show latest published events to guests
            $events = Event::published()->latest()->get();
            return view('dashboard.customer', ['user' => $guest, 'events' => $events]);
        }

        // If logged in, show role-specific dashboard
        if ($user->isOrganizer()) {
            // organizer sees only their events
            $events = Event::where('user_id', $user->id)->latest()->get();
            return view('dashboard.organizer', ['user' => $user, 'events' => $events]);
        }

        // default customer view shows published events
        $events = Event::published()->latest()->get();
        return view('dashboard.customer', ['user' => $user, 'events' => $events]);
    }
}
