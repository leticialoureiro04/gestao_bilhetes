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
        Schema::table('seats', function (Blueprint $table) {
            $table->integer('x_position')->nullable()->after('status'); // Coordenada X do lugar
            $table->integer('y_position')->nullable()->after('x_position'); // Coordenada Y do lugar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropColumn(['x_position', 'y_position']);
        });
    }
};

