<?php

use App\Models\User;
use App\Models\Year;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it shows a list of years', function () {
    $response = $this->get('/api/v1/years');

    $response->assertStatus(200);
});

test('it creates a year', function () {
    Year::factory()->create();

    $this->assertDatabaseCount(Year::class, 1);
});

test('it creates 10 years', function () {
    Year::factory()->count(9)->create();

    Year::factory()->create([
        'current_year'  =>  1
    ]);

    $this->assertDatabaseCount(Year::class, 10);
});

test('it shows an individual year', function () {
    $year = Year::factory()->create();

    $this->assertDatabaseCount(Year::class, 1);

    $response = $this->getJson("/api/v1/years/{$year->id}");

    $response->assertStatus(200);
});

test('it posts a year', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('POST', '/api/v1/years', [
                'year' => '1999-2000',
                'current_year' => 0
            ]);

    $response->assertStatus(201);

    $this->assertDatabaseCount(Year::class, 1);

    $response = $this->getJson("/api/v1/years/1");

    $response->assertStatus(200);
});

test('it updates a year', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $updatedYear = ['year' => '1900-1901', 'current_year' => $year->current_year];

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('PUT', "/api/v1/years/{$year->id}", $updatedYear);

    $response->assertStatus(200);

    $this->assertDatabaseHas(Year::class, $updatedYear);
});

test('it deletes a year', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $token = $user->createToken('api-token')->plainTextToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->json('delete', "/api/v1/years/{$year->id}");

    $response->assertStatus(200);

    $this->assertDatabaseCount(Year::class, 0);
});

test('it denies a non-admin the ability to create a year', function () {
    $user = User::factory()->create([
        'is_admin'  =>  0
    ]);

    $year = Year::factory()->create();

    $response = $this->json('POST', '/api/v1/years', [
        'year' => '1999-2000',
        'current_year' => 0
    ]);

    $response->assertStatus(401);
});

test('it denies a non-admin the ability to update a year', function () {
    $user = User::factory()->create([
        'is_admin'  =>  0
    ]);

    $year = Year::factory()->create();

    $updatedYear = ['year' => '1900-1901', 'current_year' => $year->current_year];

    $response = $this->json('PUT', "/api/v1/years/{$year->id}", $updatedYear);

    $response->assertStatus(401);
});

test('it denies a non-admin the ability to delete a year', function () {
    $user = User::factory()->create([
        'is_admin'  =>  1
    ]);

    $year = Year::factory()->create();

    $response = $this->json('delete', "/api/v1/years/{$year->id}");

    $response->assertStatus(401);
});