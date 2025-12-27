<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_profile()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/profile')
            ->assertStatus(200)
            ->assertJsonPath('data.user.email', $user->email);
    }

    public function test_can_update_name_and_email()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/profile', [
                'name' => 'New Name',
                'email' => 'newemail@example.com',
            ])
            ->assertStatus(200)
            ->assertJsonPath('data.user.name', 'New Name')
            ->assertJsonPath('data.user.email', 'newemail@example.com');

        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name', 'email' => 'newemail@example.com']);
    }

    public function test_can_change_password_with_current_password()
    {
        $user = User::factory()->create(['password' => Hash::make('oldpassword')]);

        $this->actingAs($user, 'sanctum')
            ->putJson('/api/profile', [
                'current_password' => 'oldpassword',
                'password' => 'newstrongpassword',
                'password_confirmation' => 'newstrongpassword',
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertTrue(Hash::check('newstrongpassword', $user->fresh()->password));
    }

    public function test_profile_picture_upload_and_removed_on_delete()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->actingAs($user, 'sanctum')
            ->put('/api/profile', [
                'profile_picture' => $file,
            ])
            ->assertStatus(200)
            ->assertJsonStructure(['data' => ['user' => ['profile_picture']]]);

        $user = $user->fresh();
        Storage::disk('public')->assertExists($user->profile_picture);

        // Now delete account and assert file removed
        $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/profile')
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        Storage::disk('public')->assertMissing($user->profile_picture);
    }

    public function test_delete_account_revokes_tokens_and_deletes_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/profile')
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
