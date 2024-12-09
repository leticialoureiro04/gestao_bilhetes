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
            $table->unsignedBigInteger('stand_id')->after('id'); // FK para stands
            $table->integer('row_number')->after('stand_id'); // Número da linha
            $table->integer('seat_number')->after('row_number'); // Número do assento na linha

            // Definir a chave estrangeira
            $table->foreign('stand_id')->references('id')->on('stands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->dropForeign(['stand_id']); // Remove a chave estrangeira
            $table->dropColumn(['stand_id', 'row_number', 'seat_number']); // Remove as colunas adicionadas
        });
    }
};
