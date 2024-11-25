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
        Schema::table('invoices', function (Blueprint $table) {
            // Relacionar com a tabela 'users' para buscar nome e email
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // Campos adicionais
            $table->string('title')->nullable()->after('ticket_id'); // Ex.: título da fatura
            $table->timestamp('expiration')->nullable()->after('issue_date'); // Data de expiração
            $table->decimal('saldo', 10, 2)->nullable()->after('total_amount'); // Saldo da fatura
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Remover as colunas
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'title', 'expiration', 'saldo']);
        });
    }
};
