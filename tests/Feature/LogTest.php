<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\ActivityLog;

class LogTest extends TestCase
{
    use RefreshDatabase;

    public function test_logs_endpoint_requires_auth()
    {
        $resp = $this->getJson('/api/logs');
        $resp->assertStatus(401);
    }

    public function test_authenticated_user_can_list_logs()
    {
        $user = User::factory()->create();
        // create a few logs
        ActivityLog::record($user->id, 'test:one', null, []);
        ActivityLog::record($user->id, 'test:two', null, []);

        $this->actingAs($user, 'sanctum');
        $resp = $this->getJson('/api/logs');
        $resp->assertStatus(200);
        $json = $resp->json();
        $this->assertTrue($json['status']);
        $this->assertArrayHasKey('data', $json);
        $this->assertCount(2, $json['data']);
        $this->assertArrayHasKey('meta', $json);
    }
}
