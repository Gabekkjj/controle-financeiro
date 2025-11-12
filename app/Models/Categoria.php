<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    /**
     * Diz ao Laravel o nome correto da nossa tabela.
     */
    protected $table = 'categorias';

    /**
     * Define a chave primária para bater com o nosso diagrama.
     */
    protected $primaryKey = 'id_categoria';

    /**
     * A CORREÇÃO: Define quais campos são "preenchíveis"
     * em massa a partir de um formulário.
     */
    protected $fillable = [
        'nome',
        'tipo',
    ];

    /**
     * Relacionamento: Uma categoria pode estar em muitas transações.
     */
    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'id_categoria', 'id_categoria');
    }
}