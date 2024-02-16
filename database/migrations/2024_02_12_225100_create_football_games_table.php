<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('football_games', function (Blueprint $table) {
            $table->id();
            $table->date('game_date');
            $table->time('game_time')->nullable();
            $table->foreignId('home_team_id');
            $table->foreignId('away_team_id');
            $table->string('home_team_final_score')->nullable();
            $table->string('away_team_final_score')->nullable();
            $table->foreignId('winning_team')->nullable();
            $table->foreignId('losing_team')->nullable();
            $table->unsignedBigInteger('tournament_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->string('notes')->nullable();
            $table->string('neutral_location_name')->nullable();
            $table->timestamps();

            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('away_team_id')->references('id')->on('teams');
            $table->foreign('winning_team')->references('id')->on('teams');
            $table->foreign('losing_team')->references('id')->on('teams');
            $table->foreign('school_year_id')->references('id')->on('years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('football_games');
    }
};
