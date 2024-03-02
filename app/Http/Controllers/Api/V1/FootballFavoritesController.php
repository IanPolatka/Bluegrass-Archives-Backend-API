<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class FootballFavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $favoritesCache = Cache::get("favorites".Auth::user()->id, null);
        
        if($favoritesCache){
            return TeamResource::collection($favoritesCache);
        }

        $favorites = DB::table('team_user')
                    ->join('teams', 'team_user.team_id', '=', 'teams.id')
                    ->join('users', 'team_user.user_id', '=', 'users.id')
                    ->orderBy('teams.name', 'ASC')
                    ->select('teams.*')
                    ->where('users.id', Auth::user()->id)
                    ->get();
                  
        Cache::forever("favorites".Auth::user()->id, $favorites);

        return TeamResource::collection($favorites);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Team $team)
    {
        Auth::user()->teams()->syncWithoutDetaching($team->id);

        Cache::forget("favorites".Auth::user()->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        Auth::user()->teams()->detach($team->id);

        Cache::forget("favorites".Auth::user()->id);
    }
}
