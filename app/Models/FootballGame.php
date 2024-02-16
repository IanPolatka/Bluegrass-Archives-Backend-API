<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballGame extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_date',
        'game_time',
        'home_team_id',
        'away_team_id',
        'home_team_final_score',
        'away_team_final_score',
        'winning_team',
        'losing_team',
        'tournament_id',
        'school_year_id',
        'notes',
        'neutral_location_name',
    ];

    public function home_team()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function away_team()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function school_year()
    {
        return $this->belongsTo(Year::class, 'school_year_id');
    }
}
