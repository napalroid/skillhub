<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'user_id' => User::factory(),
        'subcategory_id' => Subcategory::factory(),
        'title' => fake()->sentence(4),
        'description' => fake()->paragraph(),
        'price' => fake()->numberBetween(10000, 200000),
        'status' => 'approved',
    ];
}
}