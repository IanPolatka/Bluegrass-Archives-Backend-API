<?php

namespace Database\Factories;

use App\Http\Resources\TeamResource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class FootballGameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_date' =>  fake()->date(),
            'game_time' =>  fake()->time(),
            'home_team_id'  =>  1,
            'away_team_id'  =>  2,
            'home_team_final_score'  =>  '12',
            'away_team_final_score'  =>  '24',
            'winning_team'  =>  2,
            'losing_team'   =>  1,
            'tournament_id' => 'tournament name',
            'school_year_id'    =>  1,
            'notes' =>  'Notes',
            'neutral_location_name' =>  'neutral location name',
        ];
    }
}
