<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\YearController;
use App\Http\Controllers\Api\V1\CoachesController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\TournamentsController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\FootballGameController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\TournamentGamesController;
use App\Http\Controllers\Api\V1\Auth\PasswordUpdateController;
use App\Http\Controllers\Api\V1\FootballClassController;
use App\Http\Controllers\Api\V1\FootballTeamScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     Route::get('profile', [ProfileController::class, 'show']);
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put('password', PasswordUpdateController::class);

    Route::post('auth/logout', LogoutController::class);
});

Route::post('auth/register', RegisterController::class);
Route::post('auth/login', LoginController::class);

Route::get('teams', [TeamController::class, 'index']);

Route::get('years', [YearController::class, 'index']);
Route::get('years/{year:id}', [YearController::class, 'show']);

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::post('years', [YearController::class, 'store']);
    Route::put('years/{year}', [YearController::class, 'update']);
    Route::delete('years/{year}', [YearController::class, 'destroy']);
});

Route::get('/football/today', [FootballGameController::class, 'index']);
Route::get('/football/future', [FootballGameController::class, 'future']);
Route::get('/football/past', [FootballGameController::class, 'past']);
Route::get('/football/classes', [FootballClassController::class, 'index']);
Route::get('/football/classes/{team:slug}/{year}', [FootballClassController::class, 'show']);
Route::get('/football/classes/standings/{year}/{class}', [FootballClassController::class, 'yearShow']);

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::get('football/{id}', [FootballGameController::class, 'show']);
    Route::post('football', [FootballGameController::class, 'store']);
    Route::put('football/{id}', [FootballGameController::class, 'update']);
    Route::delete('football/{id}', [FootballGameController::class, 'destroy']);
});

Route::get('/football/schedule/{team:slug}', [FootballTeamScheduleController::class, 'index']);
Route::get('/football/schedule/{team:slug}/{year}', [FootballTeamScheduleController::class, 'year']);

Route::get('/tournaments', [TournamentsController::class, 'index']);
Route::get('/tournaments/{tournament}', [TournamentsController::class, 'show']);
Route::get('/tournaments/{tournament}/games', [TournamentGamesController::class, 'show']);

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::post('/tournaments', [TournamentsController::class, 'store']);
    Route::put('/tournaments/{tournament}', [TournamentsController::class, 'update']);
    Route::delete('/tournaments/{id}', [TournamentsController::class, 'destroy']);
});

Route::get('/coaches', [CoachesController::class, 'index']);
Route::get('/coaches/{coach}', [CoachesController::class, 'show']);
Route::get('/coaches/{coach}/summary', [CoachesController::class, 'summary']);
Route::get('/coaches/{coach}/team-summary', [CoachesController::class, 'teamHistory']);

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::post('/coaches', [CoachesController::class, 'store']);
    Route::put('/coaches/{coach}', [CoachesController::class, 'update']);
    Route::delete('/coaches/{id}', [CoachesController::class, 'destroy']);
});