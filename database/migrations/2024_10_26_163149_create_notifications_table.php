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
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('id'); // ID com auto-incremento
            $table->unsignedBigInteger('user_id'); // FK para users
            $table->text('content'); // Conteúdo da notificação
            $table->timestamp('send_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Data de envio
            $table->enum('status', ['enviada', 'pendente'])->default('pendente'); // Status da notificação
            $table->timestamps(); // Campos created_at e updated_at

            // Definindo a chave estrangeira
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
