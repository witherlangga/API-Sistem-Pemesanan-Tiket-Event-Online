<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_creates_user_and_redirects_to_dashboard()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'strongpassword',
            'password_confirmation' => 'strongpassword',
            'role' => 'customer',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_register_as_organizer_sets_role()
    {
        $response = $this->post('/register', [
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => 'strongpassword',
            'password_confirmation' => 'strongpassword',
            'role' => 'organizer',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'org@example.com', 'role' => 'organizer']);

        // Ensure profile fields are still present in schema but not set during registration
        $this->assertNull(User::where('email', 'org@example.com')->first()->company_name);
    }

    public function test_login_and_dashboard_customer()
    {
        $user = User::factory()->create(['password' => Hash::make('secret'), 'role' => 'customer']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect('/dashboard');
        $this->actingAs($user)->get('/dashboard')->assertSee('Customer Dashboard');

        // Ensure root shows customer dashboard for guest
        $this->get('/')->assertSee('Customer Dashboard');
    }

    public function test_dashboard_shows_organizer_for_organizer_role()
    {
        $user = User::factory()->create(['role' => 'organizer']);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertSee('Organizer Dashboard');
    }
}
