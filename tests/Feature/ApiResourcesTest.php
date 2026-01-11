<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiResourcesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_index_returns_ok()
    {
        Event::factory()->count(3)->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200);
        $response->assertJsonStructure(['success','message','data']);
    }

    public function test_create_event_requires_auth()
    {
        $payload = [
            'title' => 'Test Event',
            'category' => 'Seminar',
            'location' => 'Jakarta',
            'event_date' => now()->addDays(10)->format('Y-m-d'),
            'event_time' => '10:00:00',
            'capacity' => 100,
        ];

        $resp = $this->postJson('/api/events', $payload);
        $resp->assertStatus(401);
    }

    public function test_organizer_can_create_ticket()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);

        $this->actingAs($organizer, 'sanctum');

        $event = Event::factory()->create(['user_id' => $organizer->id]);

        $payload = [
            'event_id' => $event->id,
            'name' => 'VIP',
            'price' => 100000,
            'quota' => 50,
            'is_active' => true,
        ];

        $resp = $this->postJson('/api/tickets', $payload);
        $resp->assertStatus(201);
        $resp->assertJsonPath('data.name', 'VIP');
    }
}
