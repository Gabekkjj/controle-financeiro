<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cofrinho extends Model
{
    use HasFactory;

    /**
     * Define a chave primária correta.
     */
    protected $primaryKey = 'id_cofrinho';

    /**
     * Define o nome da tabela (Laravel acertaria, mas é boa prática).
     */
    protected $table = 'cofrinhos';

    /**
     * Define os campos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'id_usuario',
        'nome',
        'meta',
    ];

    /**
     * Relacionamento: Um cofrinho pertence a um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    /**
     * Relacionamento: Um cofrinho tem muitas movimentações.
     */
    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoCofrinho::class, 'id_cofrinho', 'id_cofrinho');
    }

    /**
     * Função extra para calcular o saldo atual do cofrinho.
     */
    public function getSaldoAtualAttribute()
    {
        $depositos = $this->movimentacoes()->where('tipo', 'deposito')->sum('valor');
        $retiradas = $this->movimentacoes()->where('tipo', 'retirada')->sum('valor');
        return $depositos - $retiradas;
    }
}