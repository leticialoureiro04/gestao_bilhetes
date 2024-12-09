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
        Schema::create('seats', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID com auto-incremento
            $table->unsignedBigInteger('stadium_plan_id'); // FK para stadium_plans
            $table->unsignedBigInteger('seat_type_id'); // FK para seat_types
            $table->enum('status', ['disponível', 'reservado', 'vendido'])->default('disponível'); // Status do assento
            $table->timestamps(); // Campos created_at e updated_at

            // Definindo as chaves estrangeiras
            $table->foreign('stadium_plan_id')->references('id')->on('stadium_plans')->onDelete('cascade');
            $table->foreign('seat_type_id')->references('id')->on('seat_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
