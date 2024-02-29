<?php

use App\Models\User;
use App\Models\Coach;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it shows a list of all coaches', function () {
    $response = $this->get('/api/v1/coaches');

    $response->assertStatus(200);
});

test('it shows a list of ten coaches', function () {
    $tournaments = Coach::factory()->count(10)->create();

    $this->assertDatabaseCount(Coach::class,10);
    
    $response = $this->get('/api/v1/coaches');

    $response->assertStatus(200);

    $response->assertJsonCount(10, 'data');
});

test('it allows and admin to create a coach', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('POST', '/api/v1/coaches', [
                'name' =>  fake()->name(),
            ]);

    $response->assertStatus(201);

    $this->assertDatabaseCount(Coach::class,1);
    
    $response = $this->get("/api/v1/coaches/1");

    $response->assertStatus(200);

    $response->assertJsonCount(2, 'data');
});

test('it doesn\'t allow a non-admin to create a coach', function () {
    $user = User::factory()->create([
        'is_admin'  =>  0
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('POST', '/api/v1/coaches', [
                'name' =>  fake()->name(),
            ]);

    $response->assertStatus(403);

    $this->assertDatabaseCount(Coach::class,0);
    
    $response = $this->get("/api/v1/coaches/1");

    $response->assertStatus(404);
});

test('it allows and admin to update a coach', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $coach = Coach::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('PUT', "/api/v1/coaches/{$coach->id}", [
                'name' =>  "My new name",
            ]);

    $response->assertStatus(200);

    $this->assertDatabaseCount(Coach::class,1);
    
    $response = $this->get("/api/v1/coaches/1");

    $response->assertStatus(200);

    $response->assertJsonCount(1);

    $response->assertJsonFragment(['name' => "My new name"]);
});

test('it doesn\'t allow a non-admin to update a coach', function () {
    $user = User::factory()->create([
        'is_admin'  =>  0
    ]);

    $coach = Coach::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('PUT', "/api/v1/coaches/{$coach->id}", [
                'name' =>  "Broken",
            ]);

    $response->assertStatus(403);
});
