<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
            $teams = Team::with('creator')->orderBy('name')->where('state', 'ky')->paginate();

            return TeamResource::collection($teams);
    }

    /**
     * Display a listing of the resource.
     */
    public function all(Request $request)
    {
            $teams = Team::with('creator')->orderBy('name')->where('state', 'ky')->get();

            return TeamResource::collection($teams);
    }

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
    public function show(Team $team)
    {
        return new TeamResource($team);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        //
    }
}
