<?php
// Ficheiro: database/migrations/..._create_categorias_table.php

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
        // Este é o código que será executado
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('id_categoria'); // Chave primária do seu diagrama
            $table->string('nome');     // <-- A COLUNA QUE FALTAVA
            $table->string('tipo');     // <-- Esta também faltava
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};