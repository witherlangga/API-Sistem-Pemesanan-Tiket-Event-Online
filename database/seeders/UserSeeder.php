<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat organizer sample
        User::create([
            'name' => 'Organizer Example',
            'email' => 'organizer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'organizer',
        ]);

        // Buat customer sample
        User::create([
            'name' => 'Customer Example',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        // Buat beberapa customer lagi
        User::factory(5)->create(['role' => 'customer']);
    }
}