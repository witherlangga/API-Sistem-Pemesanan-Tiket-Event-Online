<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $title = $this->faker->sentence(5);
        $date = $this->faker->dateTimeBetween('now', '+90 days');

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'description' => $this->faker->paragraphs(3, true),
            'category' => $this->faker->randomElement(['Workshop', 'Seminar', 'Meetup', 'Webinar']),
            'location' => $this->faker->city,
            'address' => $this->faker->address,
            'poster' => 'images/posters/poster-' . $this->faker->numberBetween(1,20) . '.svg',
            'event_date' => $date->format('Y-m-d'),
            'event_time' => $date->format('H:i:s'),
            'capacity' => $this->faker->numberBetween(50, 1000),
            'status' => 'published',
            'published_at' => now(),
        ];
    }
}
