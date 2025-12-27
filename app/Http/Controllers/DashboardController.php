<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // If guest, show the customer dashboard as the public/home landing
        if (! $user) {
            // Provide a lightweight guest object for the view
            $guest = (object) ['name' => 'Guest'];
            return view('dashboard.customer', ['user' => $guest]);
        }

        // If logged in, show role-specific dashboard
        if ($user->isOrganizer()) {
            return view('dashboard.organizer', ['user' => $user]);
        }

        return view('dashboard.customer', ['user' => $user]);
    }
}
