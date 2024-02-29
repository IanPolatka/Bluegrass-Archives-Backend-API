<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TournamentGame>
 */
class TournamentGameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tournament_id'  =>  fake()->numberBetween(1, 5),
            'round'   =>  fake()->numberBetween(1, 5),
            'game_id'   =>  fake()->numberBetween(1, 5),
            'hide_game_from_bracket'    =>  fake()->numberBetween(0, 1),
            'position' => fake()->numberBetween(1, 5)
        ];
    }
}
