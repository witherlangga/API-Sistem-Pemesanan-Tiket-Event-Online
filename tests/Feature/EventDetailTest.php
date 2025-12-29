<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_event_detail_shows_page_and_buy_button()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);

        $event = Event::factory()->create([
            'user_id' => $organizer->id,
            'status' => 'published',
        ]);

        $this->get('/events/' . $event->id)
            ->assertStatus(200)
            ->assertSee($event->title)
            ->assertSee('Beli Tiket');
    }

    public function test_draft_event_not_visible_to_guests()
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $event = Event::factory()->create(['user_id' => $organizer->id, 'status' => 'draft']);

        $this->get('/events/' . $event->id)
            ->assertStatus(404);
    }
}
