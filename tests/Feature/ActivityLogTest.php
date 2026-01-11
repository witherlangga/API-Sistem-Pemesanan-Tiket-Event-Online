<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_log_written_to_db_on_register()
    {
        $payload = [
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $resp = $this->postJson('/api/register', $payload);
        $resp->assertStatus(201);

        // DB should contain an activity log for auth:register in `log` table
        $this->assertDatabaseHas('log', ['action' => 'auth:register']);
    }

    public function test_profile_update_logs_activity()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        // remove any existing activity file
        $files = glob(storage_path('logs/activity*.log'));
        foreach ($files as $f) { @unlink($f); }

        $resp = $this->putJson('/api/profile', ['name' => 'New Name']);
        $resp->assertStatus(200);

        // Assert record exists in `log` table and is associated with the user
        $this->assertDatabaseHas('log', ['action' => 'profile:update', 'user_id' => $user->id]);
    }
}
