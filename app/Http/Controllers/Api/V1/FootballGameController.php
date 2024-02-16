<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\FootballGame;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FootballGameResource;

class FootballGameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = FootballGame::with(['home_team', 'away_team', 'school_year'])->whereDate('game_date', '=', Carbon::now('America/New_York')->subHours(10)->format('Y-m-d'))->get();
        return FootballGameResource::collection($games);
    }

    /**
     * Display a listing of the resource from the past.
     */
    public function past()
    {
        $pastDate = FootballGame::where('game_date','<', Carbon::now('America/New_York')->subHours(10)->format('Y-m-d'))->orderBy('game_date')->pluck('game_date')->last();
        $games = FootballGame::where('game_date', $pastDate)
            ->with('home_team')
            ->with('away_team')
            ->with('school_year')
            ->get();
        
        return FootballGameResource::collection($games);
    }

    /**
     * Display a listing of the resource from the future.
     */
    public function future()
    {
        $futureDate = FootballGame::where('game_date','>', Carbon::now('America/New_York')->subHours(10)->format('Y-m-d'))->orderBy('game_date')->pluck('game_date')->first();
        $games = FootballGame::where('game_date', $futureDate)
            ->with('home_team')
            ->with('away_team')
            ->with('school_year')
            ->get();
        
        return FootballGameResource::collection($games);
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
