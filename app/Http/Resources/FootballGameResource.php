<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FootballGameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    =>  $this->id,
            'game_date' =>  $this->game_date,
            'game_time' =>  $this->game_time,
            'home_team_id'  =>  $this->home_team_id,
            'home_team'  =>  $this->whenLoaded('home_team', fn () => TeamResource::make($this->home_team)),
            'away_team'  =>  $this->whenLoaded('away_team', fn () => TeamResource::make($this->away_team)),
            'home_team_final_score'  =>  $this->home_team_final_score,
            'away_team_final_score'  =>  $this->away_team_final_score,
            'winning_team'  =>  $this->winning_team,
            'losing_team'   =>  $this->losing_team,
            'the_winner'  =>  $this->whenLoaded('the_winner', fn () => TeamResource::make($this->the_winner)),
            'the_loser'  =>  $this->whenLoaded('the_loser', fn () => TeamResource::make($this->the_loser)),
            'tournament_id' => $this->tournament_id,
            'school_year'  =>  $this->whenLoaded('school_year', fn () => YearResource::make($this->school_year)),
            'notes' =>  $this->notes,
            'neutral_location_name' =>  $this->neutral_location_name,
        ];
    }
}
