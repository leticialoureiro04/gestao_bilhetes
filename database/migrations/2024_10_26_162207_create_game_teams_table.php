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
        Schema::create('game_teams', function (Blueprint $table) {
            $table->unsignedBigInteger('game_id'); // FK para games
            $table->unsignedBigInteger('team_id'); // FK para teams
            $table->enum('role', ['home', 'away']); // Papel da equipa no jogo

            // Chave Primária Composta
            $table->primary(['game_id', 'team_id']);

            // Definindo as chaves estrangeiras
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_teams');
    }
};
