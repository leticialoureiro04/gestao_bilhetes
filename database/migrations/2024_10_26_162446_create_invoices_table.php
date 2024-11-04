<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID com auto-incremento
            $table->unsignedBigInteger('ticket_id'); // FK para tickets
            $table->decimal('total_amount', 10, 2); // Valor total da fatura
            $table->timestamp('issue_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Data de emissão
            $table->enum('status', ['paga', 'pendente', 'cancelada'])->default('pendente'); // Status da fatura
            $table->timestamps(); // Campos created_at e updated_at

            // Definindo a chave estrangeira
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
