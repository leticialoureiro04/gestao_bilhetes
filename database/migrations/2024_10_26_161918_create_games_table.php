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
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID com auto-incremento
            $table->unsignedBigInteger('stadium_id'); // Chave estrangeira para stadiums
            $table->dateTime('date_time'); // Data e hora do jogo
            $table->timestamps(); // Cria os campos created_at e updated_at

            // Definindo a chave estrangeira
            $table->foreign('stadium_id')->references('id')->on('stadiums')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
