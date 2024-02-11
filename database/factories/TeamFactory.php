<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();
        $slug = str_slug($name, '-');

        return [
            'name' => $name,
            'slug' => $slug,
            'mascot' => fake()->name(),
            'logo' => $slug,
            'city' => fake()->city(),
            'state' => fake()->state (),
            'football' => fake()->numberBetween(0, 1),
            'is_active' => fake()->numberBetween(0, 1),
            'create_by' => User::factory(),
        ];
    }
}
