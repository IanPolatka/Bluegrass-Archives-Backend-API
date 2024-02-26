<?php

use App\Models\User;
use App\Models\Year;
use App\Models\Tournament;
use Database\Factories\TournamentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it shows a list of all tournaments', function () {
    $response = $this->get('/api/v1/tournaments');

    $response->assertStatus(200);
});

test('it shows a list of ten tournaments', function () {
    Year::factory()->count(5)->create();
    $tournaments = Tournament::factory()->count(10)->create();

    $this->assertDatabaseCount(Tournament::class,10);
    
    $response = $this->get('/api/v1/tournaments');

    $response->assertStatus(200);

    $response->assertJsonCount(1);
});

test('it shows a tournament', function () {
    $year = Year::factory()->create();
    $tournament = Tournament::factory()->create([
        'year_id'   =>  $year->id,
    ]);

    $this->assertDatabaseCount(Tournament::class,1);
    
    $response = $this->get("/api/v1/tournaments/{$tournament->id}");

    $response->assertStatus(200);

    $response->assertJsonCount(1);
});

test('it allows and admin to create a tournament', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('POST', '/api/v1/tournaments', [
                'name' =>  fake()->name(),
                'year_id' => $year->id,
            ]);

    $response->assertStatus(201);

    $this->assertDatabaseCount(Tournament::class,1);
    
    $response = $this->get("/api/v1/tournaments/1");

    $response->assertStatus(200);

    $response->assertJsonCount(1);
});

test('it doesn\'t allow a non-admin to create a tournament', function () {
    $user = User::factory()->create([
        'is_admin'  =>  0
    ]);

    $year = Year::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('POST', '/api/v1/tournaments', [
                'name' =>  fake()->name(),
                'year_id' => $year->id,
            ]);

    $response->assertStatus(403);

    $this->assertDatabaseCount(Tournament::class,0);
    
    $response = $this->get("/api/v1/tournaments/1");

    $response->assertStatus(404);
});

test('it allows and admin to update a tournament', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $tournament = Tournament::factory()->create([
        'year_id'   =>  $year->id,
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('PUT', "/api/v1/tournaments/{$tournament->id}", [
                'name' =>  "My new name",
                'year_id' => $year->id,
            ]);

    $response->assertStatus(200);

    $this->assertDatabaseCount(Tournament::class,1);
    
    $response = $this->get("/api/v1/tournaments/1");

    $response->assertStatus(200);

    $response->assertJsonCount(1);

    $response->assertJsonFragment(['name' => "My new name"]);
});

test('it doesn\'t allow a non-admin to update a tournament', function () {
    $user = User::factory()->create([
        'is_admin'  =>  0
    ]);

    $year = Year::factory()->create();

    $tournament = Tournament::factory()->create([
        'name'  =>  'This is a new tournament',
        'year_id'   =>  $year->id,
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('PUT', "/api/v1/tournaments/{$tournament->id}", [
                'name' =>  "Broken",
                'year_id' => $year->id,
            ]);

    $response->assertStatus(403);

    $this->assertDatabaseCount(Tournament::class,1);
    
    $response = $this->get("/api/v1/tournaments/1");

    $response->assertStatus(200);

    $response->assertJsonFragment(['name' => "This is a new tournament"]);
});

test('it allows an admin the ability to delete a tournament', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $tournament = Tournament::factory()->create([
        'name'  =>  'This is a new tournament',
        'year_id'   =>  $year->id,
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('delete', "/api/v1/tournaments/{$tournament->id}");

    $response->assertStatus(200);

    $this->assertDatabaseCount(Tournament::class,0);
});

test('it doesn\'t allow a non-admin to delete a tournament', function () {
    $user = User::factory()->create([
        'is_admin'  =>  0
    ]);

    $year = Year::factory()->create();

    $tournament = Tournament::factory()->create([
        'name'  =>  'This is a new tournament',
        'year_id'   =>  $year->id,
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('delete', "/api/v1/tournaments/{$tournament->id}");

    $response->assertStatus(403);

    $this->assertDatabaseCount(Tournament::class,1);
    
    $response = $this->get("/api/v1/tournaments/1");

    $response->assertStatus(200);

    $response->assertJsonFragment(['name' => "This is a new tournament"]);
});