<?php

namespace Tests\Feature;

use App\Models\FootballGame;
use Tests\TestCase;
use App\Models\Team;
use App\Models\Year;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it returns a team schedule page', function() {
    $team = Team::factory()->create();

    Year::factory()->create([
        'current_year' => 1
    ]);

    $response = $this->get("/api/v1/football/schedule/{$team->slug}"); 

    $response->assertStatus(200);
});

test('it returns a team schedule page with games', function() {
    $team = Team::factory()->create();

    Team::factory()->count(100)->create();

    $year = Year::factory()->create([
        'current_year' => 1
    ]);

    $homeGames = FootballGame::factory()->count(5)->create([
        'home_team_id'  =>  $team->id,
        'school_year_id'   =>  $year->id
    ]);

    $awayGames = FootballGame::factory()->count(5)->create([
        'away_team_id'  =>  $team->id,
        'school_year_id'   =>  $year->id
    ]);

    $response = $this->get("/api/v1/football/schedule/{$team->slug}"); 

    $response->assertStatus(200);

    $response->assertJsonCount(1);
});

test('it returns a team schedule page for a specific year', function() {
    $team = Team::factory()->create();

    $year = Year::factory()->create();

    $response = $this->get("/api/v1/football/schedule/{$team->slug}/{$year->year}"); 

    $response->assertStatus(200);
});

test('it returns a team schedule page with games of a specific year', function() {
    $team = Team::factory()->create();

    Team::factory()->count(100)->create();

    $year = Year::factory()->create();

    $homeGames = FootballGame::factory()->count(5)->create([
        'home_team_id'  =>  $team->id,
        'school_year_id'   =>  $year->id
    ]);

    $awayGames = FootballGame::factory()->count(5)->create([
        'away_team_id'  =>  $team->id,
        'school_year_id'   =>  $year->id
    ]);

    $response = $this->get("/api/v1/football/schedule/{$team->slug}/{$year->year}"); 

    $response->assertStatus(200);

    $response->assertJsonCount(1);
});