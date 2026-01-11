<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class RegisterOrganizerTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_as_organizer_creates_organizer_role_and_returns_tokens()
    {
        $payload = [
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => 'strongpassword',
            'password_confirmation' => 'strongpassword',
            'role' => 'organizer',
            'company_name' => 'Contoh Corp'
        ];

        $resp = $this->postJson('/api/register', $payload);
        $resp->assertStatus(201);

        $json = $resp->json();
        $this->assertTrue($json['success']);
        $this->assertArrayHasKey('access_token', $json['data']);
        $this->assertArrayHasKey('refresh_token', $json['data']);

        $this->assertDatabaseHas('users', ['email' => 'org@example.com', 'role' => 'organizer', 'company_name' => 'Contoh Corp']);
    }
}
