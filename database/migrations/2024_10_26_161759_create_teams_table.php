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
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID com auto-incremento
            $table->string('name', 255); // Nome da equipa
            $table->string('city', 255)->nullable(); // Cidade da equipa, opcional
            $table->date('founded')->nullable(); // Data de fundação, opcional
            $table->timestamps(); // Campos created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
