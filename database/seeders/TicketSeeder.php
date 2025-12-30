<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all published events
        $events = Event::where('status', 'published')->get();

        foreach ($events as $event) {
            // Create 2-3 ticket types per event
            $ticketTypes = [
                [
                    'name' => 'Regular',
                    'description' => 'Tiket standar dengan akses penuh ke event',
                    'price' => 50000,
                    'quota' => 100,
                ],
                [
                    'name' => 'VIP',
                    'description' => 'Tiket VIP dengan akses prioritas dan merchandise',
                    'price' => 150000,
                    'quota' => 30,
                ],
                [
                    'name' => 'Early Bird',
                    'description' => 'Tiket hemat untuk pendaftar awal',
                    'price' => 35000,
                    'quota' => 50,
                    'sale_end' => now()->addDays(7),
                ],
            ];

            // Randomly select 2-3 ticket types for each event
            $selectedTypes = collect($ticketTypes)->random(rand(2, 3));

            foreach ($selectedTypes as $type) {
                Ticket::create([
                    'event_id' => $event->id,
                    'name' => $type['name'],
                    'description' => $type['description'],
                    'price' => $type['price'],
                    'quota' => $type['quota'],
                    'sold' => rand(0, (int)($type['quota'] * 0.3)), // 0-30% sold
                    'sale_start' => now(),
                    'sale_end' => $type['sale_end'] ?? $event->event_date,
                    'is_active' => true,
                ]);
            }
        }
    }
}
