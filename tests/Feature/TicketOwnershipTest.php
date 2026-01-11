<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;

class TicketOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizer_can_update_ticket_they_own()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $this->actingAs($organizer, 'sanctum');

        // create event owned by organizer via API
        $eventResp = $this->postJson('/api/events', [
            'title' => 'Owned Event',
            'category' => 'Music',
            'location' => 'Jakarta',
            'event_date' => now()->addDays(10)->toDateString(),
            'event_time' => '19:00',
            'capacity' => 100
        ]);
        $eventResp->assertStatus(201);
        $eventId = $eventResp->json('data.id');

        // create ticket for the event
        $ticketResp = $this->postJson('/api/tickets', [
            'event_id' => $eventId,
            'name' => 'GA',
            'price' => 50000,
            'quota' => 50,
            'sale_start' => now()->addDays(1)->toDateString(),
            'sale_end' => now()->addDays(5)->toDateString()
        ]);
        $ticketResp->assertStatus(201);
        $ticketId = $ticketResp->json('data.id');

        // update ticket
        $updateResp = $this->putJson("/api/tickets/{$ticketId}", [
            'name' => 'GA Early',
            'price' => 45000
        ]);
        $updateResp->assertStatus(200);
        $this->assertEquals('GA Early', $updateResp->json('data.name'));
    }

    public function test_organizer_cannot_update_ticket_they_do_not_own()
    {
        $owner = User::factory()->create(['role' => 'organizer']);
        $other = User::factory()->create(['role' => 'organizer']);

        // owner creates event and ticket
        $this->actingAs($owner, 'sanctum');
        $eventResp = $this->postJson('/api/events', [
            'title' => 'Owner Event',
            'category' => 'Music',
            'location' => 'Jakarta',
            'event_date' => now()->addDays(10)->toDateString(),
            'event_time' => '19:00',
            'capacity' => 100
        ]);
        $eventResp->assertStatus(201);
        $eventId = $eventResp->json('data.id');

        $ticketResp = $this->postJson('/api/tickets', [
            'event_id' => $eventId,
            'name' => 'GA',
            'price' => 50000,
            'quota' => 50
        ]);
        $ticketResp->assertStatus(201);
        $ticketId = $ticketResp->json('data.id');

        // other organizer tries to update
        $this->actingAs($other, 'sanctum');
        $resp = $this->putJson("/api/tickets/{$ticketId}", ['name' => 'Hacked']);
        $resp->assertStatus(403);
        $this->assertEquals(false, $resp->json('status'));
    }
}
