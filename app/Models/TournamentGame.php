<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TournamentGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id', 
        'round', 
        'game_id', 
        'hide_game_from_bracket', 
        'position'
    ];

    public function game()
    {
        return $this->belongsTo(FootballGame::class, 'game_id');
    }
}
