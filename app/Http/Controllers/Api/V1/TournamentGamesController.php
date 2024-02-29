<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Models\TournamentGame;
use App\Http\Controllers\Controller;
use App\Http\Resources\TournamentResource;
use App\Http\Resources\FootballGameResource;
use App\Http\Resources\TournamentGameResource;

class TournamentGamesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament)
    {
        $tournamentRounds = TournamentGame::where('tournament_id', $tournament->id)->get()->unique('round')->pluck('round');

        $rounds = collect();

        foreach($tournamentRounds as $round) {
            $game = TournamentGame::with('game')->where('tournament_id', $tournament->id)->where('round', $round)->orderBy('position')->get();
            $rounds[$round] = TournamentGameResource::collection($game);
        }

        $summary = array(["Tournament"=> new TournamentResource($tournament), "Rounds"=> $rounds]);
        return $summary;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
