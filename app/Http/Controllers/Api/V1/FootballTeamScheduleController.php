<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Team;
use App\Models\Year;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FootballGameResource;
use App\Models\FootballGame;

class FootballTeamScheduleController extends Controller
{
    public function index(Team $team)
    {
        $current_year = Year::where('current_year', 1)->first();
        $games = FootballGame::with('away_team')
                            ->with('home_team')
                            ->where(function ($query) use ($team) {
                                $query->where('away_team_id', '=', $team->id)
                            ->orWhere('home_team_id', '=', $team->id);
                            })
                            ->with('school_year')
                            ->where('school_year_id', $current_year->id)
                            ->orderBy('game_date')
                            ->get();
        return FootballGameResource::collection($games);
    }

    public function year(Team $team, Year $year)
    {
        $games = FootballGame::with('away_team')
                            ->with('home_team')
                            ->with('school_year')
                            ->where(function ($query) use ($team) {
                                $query->where('away_team_id', '=', $team->id)
                            ->orWhere('home_team_id', '=', $team->id);
                            })
                            ->with('the_winner')
                            ->with('the_loser')
                            ->where('school_year_id', $year->id)
                            ->orderBy('game_date')->get();

        return FootballGameResource::collection($games);
    }
}
