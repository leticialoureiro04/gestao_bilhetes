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
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID com auto-incremento
            $table->unsignedBigInteger('game_id'); // FK para games
            $table->unsignedBigInteger('seat_id'); // FK para seats
            $table->unsignedBigInteger('user_id'); // FK para users
            $table->decimal('price', 10, 2); // Preço do bilhete
            $table->enum('status', ['disponível', 'vendido'])->default('disponível'); // Status do bilhete
            $table->timestamps(); // Campos created_at e updated_at

            // Definindo as chaves estrangeiras
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
