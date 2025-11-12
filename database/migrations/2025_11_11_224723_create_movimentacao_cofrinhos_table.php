<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimentacoes_cofrinho', function (Blueprint $table) {
            $table->id('id_movimentacao');
            
            // Chave estrangeira para saber a qual cofrinho pertence
            $table->foreignId('id_cofrinho')->constrained('cofrinhos', 'id_cofrinho')->onDelete('cascade');
            
            $table->enum('tipo', ['deposito', 'retirada']); // Depósito ou Retirada
            $table->decimal('valor', 10, 2);
            $table->date('data');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_cofrinho');
    }
};