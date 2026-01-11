<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_endpoint_requires_auth()
    {
        $resp = $this->getJson('/api/users');
        $resp->assertStatus(401);
    }

    public function test_authenticated_user_can_list_users_and_filter_by_role()
    {
        User::factory()->create(['role' => 'customer']);
        User::factory()->create(['role' => 'organizer']);
        $admin = User::factory()->create();

        $this->actingAs($admin, 'sanctum');
        $resp = $this->getJson('/api/users?per_page=10');
        $resp->assertStatus(200);
        $json = $resp->json();
        $this->assertTrue($json['status']);
        $this->assertArrayHasKey('data', $json);
        $this->assertArrayHasKey('meta', $json);

        // filter by role
        $resp2 = $this->getJson('/api/users?role=organizer');
        $resp2->assertStatus(200);
        $this->assertCount(1, $resp2->json()['data']['data'] ?? $resp2->json()['data']);
    }
}
