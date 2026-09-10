<?php

namespace Database\Factories;

use App\Models\Sample;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Sample> */
class SampleFactory extends Factory
{
    protected $model = Sample::class;

    public function definition(): array
    {
        return [
            'service_id' => Service::factory(),
            'title' => fake()->sentence(3),
            'client' => fake()->company(),
            'sort_order' => 0,
        ];
    }

    public function uploaded(): static
    {
        return $this->state(fn () => ['video_path' => 'samples/films/demo.mp4']);
    }

    public function youtube(): static
    {
        return $this->state(fn () => ['video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
    }
}
