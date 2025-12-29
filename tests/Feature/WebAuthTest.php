<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class WebAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_register_creates_user_and_redirects_to_dashboard()
    {
        // Start by visiting the registration page to initialize session/CSRF token
        $this->get('/register');
        $token = session('_token');

        $response = $this->post('/register', array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'strongpassword',
            'password_confirmation' => 'strongpassword',
            'role' => 'customer',
            'phone' => '+628123456789',
            'address' => 'Jakarta, Indonesia',
        ], ['_token' => $token]));

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'phone' => '+628123456789', 'address' => 'Jakarta, Indonesia']);
    }

    public function test_register_as_organizer_sets_role()
    {
        // Start by visiting the registration page to initialize CSRF session
        $this->get('/register');
        $token = session('_token');

        $response = $this->post('/register', [
            'name' => 'Org User',
            'email' => 'org@example.com',
            'password' => 'strongpassword',
            'password_confirmation' => 'strongpassword',
            'role' => 'organizer',
            'company_name' => 'Contoh Corp',
            '_token' => $token,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'org@example.com', 'role' => 'organizer', 'company_name' => 'Contoh Corp']);

    }

    public function test_login_and_dashboard_customer()
    {
        $user = User::factory()->create(['password' => Hash::make('secret'), 'role' => 'customer']);
        /** @var \App\Models\User $user */

        // Visit the login page first to initialize session/CSRF
        $this->get('/login');
        $token = session('_token');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
            '_token' => $token,
        ]);

        $response->assertRedirect('/dashboard');
        $this->actingAs($user);
        $this->get('/dashboard')->assertSee('Featured Events');

        // Ensure root shows customer dashboard for guest
        $this->get('/')->assertSee('Featured Events');
    }

    public function test_dashboard_shows_organizer_for_organizer_role()
    {
        $user = User::factory()->create(['role' => 'organizer']);
        /** @var \App\Models\User $user */

        $this->actingAs($user);
        $this->get('/dashboard')->assertSee('Organizer Dashboard');
    }
}
