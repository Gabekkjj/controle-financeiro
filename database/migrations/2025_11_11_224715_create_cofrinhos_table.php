<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cofrinhos', function (Blueprint $table) {
            $table->id('id_cofrinho');
            
            // Chave estrangeira para saber qual utilizador é o dono
            $table->foreignId('id_usuario')->constrained('users')->onDelete('cascade');
            
            $table->string('nome'); // Ex: "Reserva de Emergência"
            $table->decimal('meta', 10, 2)->nullable(); // Meta opcional de quanto quer guardar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cofrinhos');
    }
};