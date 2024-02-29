<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Team;
use App\Models\Year;
use App\Models\Coach;
use App\Models\FootballGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamResource;
use App\Http\Resources\YearResource;
use App\Http\Resources\CoachResource;

class CoachesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coaches = Coach::paginate();

        return CoachResource::collection($coaches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->rules());

        $coach = Coach::create($request->all());

        return new CoachResource($coach);
    }

    /**
     * Display the specified resource.
     */
    public function show(Coach $coach)
    {
        return new CoachResource($coach);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coach $coach)
    {
        $request->validate($this->rules());
        $coach->update($request->all());
        return new CoachResource($coach);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Coach::destroy($id);
    }

    public function summary(Coach $coach)
    {
        $rows = DB::table('coaches')
                ->join('coach_team_year', 'coach_team_year.coach_id', '=', 'coaches.id')
                ->join('teams', 'teams.id', '=', 'coach_team_year.team_id')
                ->join('years', 'years.id', '=', 'coach_team_year.year_id')
                ->select('coaches.id as CoachId', 'coaches.name as CoachName', 'teams.id', 'teams.name as TeamName', 'years.year as SchoolYear')
                ->where('coaches.id', $coach->id)
                ->get();

        $theRows = array();
        $totalWins = 0;
        $totalLosses = 0;
        foreach ($rows as $row) {
            $coachName = $row->CoachName;
            $schoolYear = Year::where('year', $row->SchoolYear)->first();
            $wins = FootballGame::where('winning_team', $row->id)->where('school_year_id', $schoolYear->id)->count();
            $losses = FootballGame::where('losing_team', $row->id)->where('school_year_id', $schoolYear->id)->count();
            $team = Team::where('id', $row->id)->first();
            array_push($theRows, ["Coach"=>$coachName, "Year"=>$schoolYear, "Team"=>$team, "Wins"=>$wins, "Losses"=>$losses]);
            $totalWins += $wins;
            $totalLosses += $losses;
        }
        $summary = array(["Coach"=> new CoachResource($coach), "Total Wins"=>$totalWins, "Totat Losses"=>$totalLosses, "Total Seasons"=>count($theRows)]);

        return response()->json(['data' => $summary]);
    }

    public function teamHistory(Coach $coach)
    {
        $rows = DB::table('coaches')
                ->join('coach_team_year', 'coach_team_year.coach_id', '=', 'coaches.id')
                ->join('teams', 'teams.id', '=', 'coach_team_year.team_id')
                ->join('years', 'years.id', '=', 'coach_team_year.year_id')
                ->select('coaches.id as CoachId', 'coaches.name as CoachName', 'teams.id', 'teams.name as TeamName', 'years.year as SchoolYear')
                ->where('coaches.id', $coach->id)
                ->get();

        $theRows = array();
        $totalWins = 0;
        $totalLosses = 0;
        foreach ($rows as $row) {
            $coachName = $row->CoachName;
            $schoolYear = Year::where('year', $row->SchoolYear)->first();
            $wins = FootballGame::where('winning_team', $row->id)->where('school_year_id', $schoolYear->id)->count();
            $losses = FootballGame::where('losing_team', $row->id)->where('school_year_id', $schoolYear->id)->count();
            $team = Team::where('id', $row->id)->first();
            array_push($theRows, ["Coach"=> new CoachResource($coach), "Year"=> new YearResource($schoolYear), "Team"=> new TeamResource($team), "Wins"=>$wins, "Losses"=>$losses]);
            $totalWins += $wins;
            $totalLosses += $losses;
        }

        return response()->json(['data'=>$theRows]);
    }

    protected function rules()
    {
        return [
            'name' =>  'required',
        ];
    }
}
