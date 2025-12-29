<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        // Ensure we have some organizers to own events.
        if (User::where('role', 'organizer')->count() < 3) {
            User::factory(3)->create([ 'role' => 'organizer' ]);
        }

        // Create 20 events with posters
        Event::factory()->count(20)->create();
    }
}
