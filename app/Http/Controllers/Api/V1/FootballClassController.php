<?php

namespace App\Http\Controllers\Api\V1;

use stdClass;
use App\Models\Team;
use App\Models\Year;
use App\Models\FootballGame;
use Illuminate\Http\Request;
use App\Models\FootballClass;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\FootballClassResource;

class FootballClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = FootballClass::orderBy('class_name')->get();

        return FootballClassResource::collection($classes);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team, Year $year)
    {
        $khsaaClass = DB::table('football_class_team_year')
                    ->join('teams', 'football_class_team_year.team_id', '=', 'teams.id')
                    ->join('football_classes', 'football_class_team_year.class_id', '=', 'football_classes.id')
                    ->join('years', 'football_class_team_year.year_id', '=', 'years.id')
                    ->select('teams.slug as TeamSlug', 'teams.name as TeamName', 'teams.logo as TeamLogo', 'teams.id as TeamId', 'football_classes.class_name as ClassName', 'years.year as Year', 'years.id as YearId')
                    ->where('teams.id', $team->id)
                    ->where('years.id', $year->id)
                    ->first();

        if ($khsaaClass) {

            $classOpponents = DB::table('football_class_team_year')
                        ->join('teams', 'football_class_team_year.team_id', '=', 'teams.id')
                        ->join('football_classes', 'football_class_team_year.class_id', '=', 'football_classes.id')
                        ->join('years', 'football_class_team_year.year_id', '=', 'years.id')
                        ->select('teams.slug as TeamSlug', 'teams.name as TeamName', 'teams.logo as TeamLogo', 'teams.id as TeamId', 'football_classes.class_name as ClassName', 'football_classes.id as ClassId', 'years.year as Year', 'years.id as YearId')
                        ->where('Year', $year->year)
                        ->where('football_classes.id', $khsaaClass->ClassName)
                        ->get();

            $standings = collect();

            foreach ($classOpponents as $opp) {
                $wins = FootballGame::where('winning_team', $opp->TeamId)->where('school_year_id', $opp->YearId)->count();
                $losses = FootballGame::where('losing_team', $opp->TeamId)->where('school_year_id', $opp->YearId)->count();
                $standings->push(["Team"=>$opp->TeamName,"TeamSlug"=>$opp->TeamSlug,"TeamLogo"=>$opp->TeamLogo,"Year"=>$opp->Year,"Wins"=>$wins,"Losses"=>$losses]);
            }

            $sorted = $standings->sortBy([
                ['Wins', 'desc'],
                ['Losses', 'asc'],
                ['Team', 'asc'],
            ]);

            return response()->json(['data' => $sorted]);

        } else {

            return response()->json(['data' => []], 404);

        }
    }

    /**
     * Display the specified resource.
     */
    public function yearShow(Year $year, FootballClass $class)
    {
        $khsaaClass = DB::table('football_class_team_year')
                    ->join('football_classes', 'football_class_team_year.class_id', '=', 'football_classes.id')
                    ->join('years', 'football_class_team_year.year_id', '=', 'years.id')
                    ->select('football_classes.class_name as ClassName', 'years.year as Year', 'years.id as YearId')
                    ->where('years.id', $year->id)
                    ->where('football_class_team_year.class_id', $class->id)
                    ->first();

        if ($khsaaClass) {

            $classOpponents = DB::table('football_class_team_year')
                        ->join('teams', 'football_class_team_year.team_id', '=', 'teams.id')
                        ->join('football_classes', 'football_class_team_year.class_id', '=', 'football_classes.id')
                        ->join('years', 'football_class_team_year.year_id', '=', 'years.id')
                        ->select('teams.slug as TeamSlug', 'teams.name as TeamName', 'teams.logo as TeamLogo', 'teams.id as TeamId', 'football_classes.class_name as ClassName', 'football_classes.id as ClassId', 'years.year as Year', 'years.id as YearId')
                        ->where('Year', $year->year)
                        ->where('football_classes.class_name', $khsaaClass->ClassName)
                        ->get();

            $standings = collect();

            foreach ($classOpponents as $opp) {
                $wins = FootballGame::where('winning_team', $opp->TeamId)->where('school_year_id', $opp->YearId)->count();
                $losses = FootballGame::where('losing_team', $opp->TeamId)->where('school_year_id', $opp->YearId)->count();
                $standings->push(["Team"=>$opp->TeamName,"TeamSlug"=>$opp->TeamSlug,"TeamLogo"=>$opp->TeamLogo,"Year"=>$opp->Year,"Wins"=>$wins,"Losses"=>$losses]);
            }

            $sorted = $standings->sortBy([
                ['Wins', 'desc'],
                ['Losses', 'asc'],
                ['Team', 'asc'],
            ]);

            return response()->json(['data' => $sorted]);
        
        } else {

            return response()->json(['data' => []], 404);
        
        }
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
