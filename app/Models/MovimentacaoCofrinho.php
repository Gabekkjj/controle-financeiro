<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoCofrinho extends Model
{
    use HasFactory;

    /**
     * Define a chave primária correta.
     */
    protected $primaryKey = 'id_movimentacao';

    /**
     * Define o nome da tabela (Obrigatório, pois o Laravel erraria o plural).
     */
    protected $table = 'movimentacoes_cofrinho';

    /**
     * Define os campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'id_cofrinho',
        'tipo',
        'valor',
        'data',
    ];

    /**
     * Relacionamento: Uma movimentação pertence a um cofrinho.
     */
    public function cofrinho()
    {
        return $this->belongsTo(Cofrinho::class, 'id_cofrinho', 'id_cofrinho');
    }
}