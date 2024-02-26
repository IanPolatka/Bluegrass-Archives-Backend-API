<?php

use Carbon\Carbon;
use App\Models\Team;
use App\Models\User;
use App\Models\Year;
use App\Models\FootballGame;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('it shows games from today', function() {
    Year::factory()->create();
    
    Team::factory()->count(10)->create();

    FootballGame::factory()->count(10)->create([
        'game_date' =>  date('Y-m-d'),
    ]);

    $response = $this->getJson('/api/v1/football/today');

    $response->assertJsonCount(1);

    $response->assertJsonFragment(['game_date' => Carbon::now()->format('Y-m-d')]);
});

test('it shows games scheduled for a week from today', function() {
    Year::factory()->create();
    
    Team::factory()->count(10)->create();

    $games = FootballGame::factory()->count(10)->create([
        'game_date' =>  Carbon::now()->addDays(7)->format('Y-m-d'),
    ]);

    $response = $this->get('/api/v1/football/future');

    $response->assertJsonCount(1);

    $response->assertJsonFragment(['game_date' => Carbon::now()->addDays(7)->format('Y-m-d')]);
});

test('it shows games scheduled from a week ago', function() {
    Year::factory()->create();
    
    Team::factory()->count(10)->create();

    $games = FootballGame::factory()->count(10)->create([
        'game_date' =>  Carbon::now()->subDays(7)->format('Y-m-d'),
    ]);

    $response = $this->get('/api/v1/football/past');

    $response->assertJsonCount(1);

    $response->assertJsonFragment(['game_date' => Carbon::now()->subDays(7)->format('Y-m-d')]);
});

test('it can create a game', function() {
    $teamOne = Team::factory()->create();
    $teamTwo = Team::factory()->create();

    $year = Year::factory()->create();

    $game = FootballGame::factory()->create([
        'home_team_id'  =>  $teamOne->id,
        'away_team_id'  =>  $teamTwo->id,
    ]);

    $this->assertDatabaseCount(FootballGame::class, 1);
});

test('it can create ten games', function() {
    $teamOne = Team::factory()->create();
    $teamTwo = Team::factory()->create();

    $year = Year::factory()->create();
    
    FootballGame::factory()->count(10)->create([
        'home_team_id'  =>  $teamOne->id,
        'away_team_id'  =>  $teamTwo->id,
    ]);

    $this->assertDatabaseCount(FootballGame::class, 10);
});

test('it can post a football game', function() {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $teamOne = Team::factory()->create();
    $teamTwo = Team::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('POST', '/api/v1/football', [
                'game_date' =>  '2024-02-12',
                'game_time' => '',
                'home_team_id' => $teamOne->id,
                'away_team_id' => $teamTwo->id,
                'home_team_final_score' => '',
                'away_team_final_score' => '',
                'winning_team' => $teamTwo->id,
                'losing_team' => $teamOne->id,
                'tournament_id' => '',
                'school_year_id' => $year->id,
                'notes' => '',
                'neutral_location_name' => '',
            ]);

    $response->assertStatus(201);

    $this->assertDatabaseCount(FootballGame::class, 1);
});

test('it can update a football game', function() {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $teamOne = Team::factory()->create();
    $teamThree = Team::factory()->create();

    $footballGame = FootballGame::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $updatedGame = [
        'game_date' => '2015-09-01',
        'home_team_id' => $teamOne->id, 
        'away_team_id' => $teamThree->id,
        'winning_team' => $teamThree->id,
        'losing_team' => $teamOne->id,
        'school_year_id' => $year->id,
    ];

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('PUT', "/api/v1/football/{$footballGame->id}", $updatedGame);

    $response->assertStatus(200);

    $this->assertDatabaseHas(FootballGame::class, $updatedGame);
});

test('it can delete a football game', function() {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    Team::factory()->count(2)->create();

    $footballGame = FootballGame::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('delete', "/api/v1/football/{$footballGame->id}");

    $response->assertStatus(200);

    $this->assertDatabaseCount(FootballGame::class, 0);
});
