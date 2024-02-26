<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FootballResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    =>  $this->game_id,
            'game_date' =>  $this->game_date,
            'game_time' =>  $this->game_time,
            'home_team_id'  =>  $this->home_team_id,
            'home_team_name'  =>  $this->home_team_name,
            'home_team_mascot'  =>  $this->home_team_mascot,
            'home_team_city'  =>  $this->home_team_city,
            'home_team_state'  =>  $this->home_team_state,
            'home_team_logo'  =>  asset('images/team-logos/' . $this->home_team_logo),
            'away_team_name'  =>  $this->away_team_name,
            'away_team_mascot'  =>  $this->away_team_mascot,
            'away_team_city'  =>  $this->away_team_city,
            'away_team_state'  =>  $this->away_team_state,
            'away_team_logo'  =>  asset('images/team-logos/' . $this->away_team_logo),
            'home_team_final_score'  =>  $this->home_team_final_score,
            'away_team_final_score'  =>  $this->away_team_final_score,
            'neutral_location_name' =>  $this->neutral_location_name,
            'winner'    =>  $this->winner,
            'loser'    =>  $this->loser,
            'notes' =>  $this->game_notes,
            'school_year'   =>  $this->school_year,
            'school_year_id'   =>  $this->school_year_id,
            'is_winner_set' =>  $this->is_winner_set,
            'winning_team_name'    =>  $this->winning_team_name,
            'winning_team_slug'    =>  $this->winning_team_slug,
            'losing_team_name'    =>  $this->losing_team_name,
            'losing_team_slug'    =>  $this->losing_team_slug,
        ];
    }
}
