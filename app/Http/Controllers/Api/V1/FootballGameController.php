<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\FootballGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\FootballResource;
use App\Http\Resources\FootballGameResource;

class FootballGameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $today = FootballGame::where('game_date', Carbon::now('America/New_York')->subHours(10)->format('Y-m-d'))->orderBy('game_date')->pluck('game_date')->first();
        $games = DB::table('football_games as football')
                    ->join('teams as hometeam', 'football.home_team_id', '=', 'hometeam.id')
                    ->join('teams as awayteam', 'football.away_team_id', '=', 'awayteam.id')
                    ->leftJoin('teams as winningteam', 'football.winning_team', '=', 'winningteam.id')
                    ->leftJoin('teams as losingteam', 'football.losing_team', '=', 'losingteam.id')
                    ->join('years', 'football.school_year_id', '=', 'years.id')
                    ->select(
                        'football.id as game_id', 
                        'football.game_date as game_date', 
                        'football.game_time as game_time',
                        'football.winning_team as winner', 
                        'football.losing_team as loser', 
                        'football.away_team_final_score', 
                        'football.home_team_final_score',
                        'football.notes as game_notes', 
                        'football.neutral_location_name as neutral_location_name',
                        'years.year as school_year',
                        'years.id as school_year_id',
                        'hometeam.id as home_team_id',
                        'hometeam.name as home_team_name',
                        'hometeam.city as home_team_city',
                        'hometeam.state as home_team_state',
                        'hometeam.logo as home_team_logo', 
                        'hometeam.slug as home_team_slug',
                        'hometeam.mascot as home_team_mascot',
                        'awayteam.id as away_team_id', 
                        'awayteam.name as away_team_name',
                        'awayteam.city as away_team_city',
                        'awayteam.state as away_team_state', 
                        'awayteam.logo as away_team_logo', 
                        'awayteam.slug as away_team_slug',
                        'awayteam.mascot as away_team_mascot',
                        'winningteam.name as winning_team_name',
                        'winningteam.slug as winning_team_slug',
                        'losingteam.name as losing_team_name',
                        'losingteam.slug as losing_team_slug',
                        DB::raw('CASE WHEN football.winning_team IS NULL THEN "not set" ELSE "set" END as is_winner_set'),
                    )
                    ->where('game_date', $today)
                    ->orderBy('is_winner_set', 'DESC')
                    ->orderBy('home_team_name', 'ASC')
                    ->get();
        
        return FootballResource::collection($games);
    }

    /**
     * Display a listing of the resource from the past.
     */
    public function past()
    {
        $pastDate = FootballGame::where('game_date','<', Carbon::now('America/New_York')->subHours(10)->format('Y-m-d'))->orderBy('game_date')->pluck('game_date')->last();
        $games = DB::table('football_games as football')
                    ->join('teams as hometeam', 'football.home_team_id', '=', 'hometeam.id')
                    ->join('teams as awayteam', 'football.away_team_id', '=', 'awayteam.id')
                    ->leftJoin('teams as winningteam', 'football.winning_team', '=', 'winningteam.id')
                    ->leftJoin('teams as losingteam', 'football.losing_team', '=', 'losingteam.id')
                    ->join('years', 'football.school_year_id', '=', 'years.id')
                    ->select(
                        'football.id as game_id', 
                        'football.game_date as game_date', 
                        'football.game_time as game_time',
                        'football.winning_team as winner', 
                        'football.losing_team as loser', 
                        'football.away_team_final_score', 
                        'football.home_team_final_score',
                        'football.notes as game_notes', 
                        'football.neutral_location_name as neutral_location_name',
                        'years.year as school_year',
                        'years.id as school_year_id',
                        'hometeam.id as home_team_id',
                        'hometeam.name as home_team_name',
                        'hometeam.city as home_team_city',
                        'hometeam.state as home_team_state',
                        'hometeam.logo as home_team_logo', 
                        'hometeam.slug as home_team_slug',
                        'hometeam.mascot as home_team_mascot',
                        'awayteam.id as away_team_id', 
                        'awayteam.name as away_team_name',
                        'awayteam.city as away_team_city',
                        'awayteam.state as away_team_state', 
                        'awayteam.logo as away_team_logo', 
                        'awayteam.slug as away_team_slug',
                        'awayteam.mascot as away_team_mascot',
                        'winningteam.name as winning_team_name',
                        'winningteam.slug as winning_team_slug',
                        'losingteam.name as losing_team_name',
                        'losingteam.slug as losing_team_slug',
                        DB::raw('CASE WHEN football.winning_team IS NULL THEN "not set" ELSE "set" END as is_winner_set'),
                    )
                    ->where('game_date', $pastDate)
                    ->orderBy('is_winner_set', 'DESC')
                    ->orderBy('home_team_name', 'ASC')
                    ->get();
        
        return FootballResource::collection($games);
    }

    /**
     * Display a listing of the resource from the future.
     */
    public function future()
    {
        $futureDate = FootballGame::where('game_date','>', Carbon::now('America/New_York')->subHours(10)->format('Y-m-d'))->orderBy('game_date')->pluck('game_date')->first();
        $games = DB::table('football_games as football')
                    ->join('teams as hometeam', 'football.home_team_id', '=', 'hometeam.id')
                    ->join('teams as awayteam', 'football.away_team_id', '=', 'awayteam.id')
                    ->leftJoin('teams as winningteam', 'football.winning_team', '=', 'winningteam.id')
                    ->leftJoin('teams as losingteam', 'football.losing_team', '=', 'losingteam.id')
                    ->join('years', 'football.school_year_id', '=', 'years.id')
                    ->select(
                        'football.id as game_id', 
                        'football.game_date as game_date', 
                        'football.game_time as game_time',
                        'football.winning_team as winner', 
                        'football.losing_team as loser', 
                        'football.away_team_final_score', 
                        'football.home_team_final_score',
                        'football.notes as game_notes', 
                        'football.neutral_location_name as neutral_location_name',
                        'years.year as school_year',
                        'years.id as school_year_id',
                        'hometeam.id as home_team_id',
                        'hometeam.name as home_team_name',
                        'hometeam.city as home_team_city',
                        'hometeam.state as home_team_state',
                        'hometeam.logo as home_team_logo', 
                        'hometeam.slug as home_team_slug',
                        'hometeam.mascot as home_team_mascot',
                        'awayteam.id as away_team_id', 
                        'awayteam.name as away_team_name',
                        'awayteam.city as away_team_city',
                        'awayteam.state as away_team_state', 
                        'awayteam.logo as away_team_logo', 
                        'awayteam.slug as away_team_slug',
                        'awayteam.mascot as away_team_mascot',
                        'winningteam.name as winning_team_name',
                        'winningteam.slug as winning_team_slug',
                        'losingteam.name as losing_team_name',
                        'losingteam.slug as losing_team_slug',
                        DB::raw('CASE WHEN football.winning_team IS NULL THEN "not set" ELSE "set" END as is_winner_set'),
                    )
                    ->where('game_date', $futureDate)
                    ->orderBy('is_winner_set', 'DESC')
                    ->orderBy('home_team_name', 'ASC')
                    ->get();
        
        return FootballResource::collection($games);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        $game = FootballGame::create($request->all());

        return new FootballGameResource($game);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $game = FootballGame::with('home_team')->with('away_team')->with('school_year')->find($id);
        return new FootballGameResource($game);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $game = FootballGame::find($id);
        $request->validate($this->rules());
        $game->update($request->all());
        return new FootballGameResource($game);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return FootballGame::destroy($id);
    }

    protected function rules()
    {
        return [
            'game_date' =>  'required|date',
            'home_team_id'  =>  'required|exists:teams,id',
            'away_team_id'  =>  'required|exists:teams,id',
            'school_year_id'    =>  'required|exists:years,id',
        ];
    }
}
