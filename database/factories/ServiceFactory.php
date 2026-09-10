<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Service> */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'tagline' => fake()->sentence(5),
            'description' => fake()->paragraph(),
            'accent_from' => '#15D9A1',
            'accent_to' => '#00615C',
            'sort_order' => fake()->numberBetween(0, 10),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
