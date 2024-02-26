<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tournament;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TournamentResource;

class TournamentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournaments = Tournament::with('school_year')->orderBy('name')->get();

        return TournamentResource::collection($tournaments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        $tournament = Tournament::create($request->all());

        return new TournamentResource($tournament);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament)
    {

        return new TournamentResource($tournament);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournament $tournament)
    {
        $request->validate($this->rules());
        $tournament->update($request->all());
        return new TournamentResource($tournament);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Tournament::destroy($id);
    }

    protected function rules()
    {
        return [
            'name' =>  'required',
            'year_id'    =>  'required|exists:years,id',
        ];
    }
}
