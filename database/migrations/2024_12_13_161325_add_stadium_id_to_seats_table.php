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
        Schema::table('seats', function (Blueprint $table) {
            if (!Schema::hasColumn('seats', 'stadium_id')) { // Verifica se a coluna já não existe
                $table->unsignedBigInteger('stadium_id')->nullable()->after('id'); // Adiciona a coluna, permitindo NULL inicialmente
                $table->foreign('stadium_id')->references('id')->on('stadiums')->onDelete('cascade'); // Define a chave estrangeira
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('seats', function (Blueprint $table) {
            if (Schema::hasColumn('seats', 'stadium_id')) { // Verifica se a coluna existe antes de tentar remover
                $table->dropForeign(['stadium_id']); // Remove a chave estrangeira
                $table->dropColumn('stadium_id'); // Remove a coluna
            }
        });
    }
};

