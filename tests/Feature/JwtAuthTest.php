<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JwtAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_jwt_tokens()
    {
        $payload = [
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $resp = $this->postJson('/api/register', $payload);
        $resp->assertStatus(201);
        $resp->assertJsonStructure(['success', 'message', 'data' => ['user', 'access_token', 'refresh_token']]);
    }

    public function test_login_jwt_and_access_protected_route()
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $resp = $this->postJson('/api/auth/login-jwt', ['email' => $user->email, 'password' => 'secret']);
        $resp->assertStatus(200);
        $resp->assertJsonStructure(['success', 'message', 'data' => ['access_token','refresh_token']]);

        $access = $resp->json('data.access_token');

        // Access a protected route (profile)
        $profile = $this->withHeaders(['Authorization' => 'Bearer ' . $access])->getJson('/api/profile');
        $profile->assertStatus(200);
    }

    public function test_refresh_rotates_tokens()
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);
        $resp = $this->postJson('/api/auth/login-jwt', ['email' => $user->email, 'password' => 'secret']);
        $refresh = $resp->json('data.refresh_token');

        $r2 = $this->postJson('/api/auth/refresh', ['refresh_token' => $refresh]);
        $r2->assertStatus(200);
        $r2->assertJsonStructure(['success', 'data' => ['access_token','refresh_token']]);
    }
}
