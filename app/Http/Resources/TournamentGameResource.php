<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TournamentGameResource extends JsonResource
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
            'position'  =>  $this->position,
            'game'   =>  FootballGameResource::make($this->game),
            'round' =>  $this->round,
            'hide_game_from_bracket'  =>  $this->hide_game_from_bracket,
        ];
    }
}
