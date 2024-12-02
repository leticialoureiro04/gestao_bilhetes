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
        Schema::table('users', function (Blueprint $table) {
            $table->string('country')->default('PT')->after('password'); // País por defeito "PT"
            $table->string('vatNumber')->default('999999990')->after('country'); // Número de Contribuinte por defeito
            $table->string('id_gesfaturacao')->nullable()->after('vatNumber'); // ID da API GESFaturação
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['country', 'vatNumber', 'id_gesfaturacao']);
        });
    }
};
