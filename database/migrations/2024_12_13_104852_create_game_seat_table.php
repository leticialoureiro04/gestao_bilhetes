<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('game_seat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id'); // Referência ao jogo
            $table->unsignedBigInteger('seat_id'); // Referência ao lugar
            $table->string('status')->default('disponível'); // Estado inicial
            $table->timestamps(); // Para controlar criação e atualizações

            // Chaves estrangeiras
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_seat');
    }
};
