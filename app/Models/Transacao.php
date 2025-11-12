<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacoes';
    protected $primaryKey = 'id_transacao';

    protected $fillable = [
        'id_usuario',
        'id_categoria',
        'valor',
        'data',
        'descricao',
        'tipo',
    ];

    /**
     * Diz ao Laravel para usar 'id_transacao' nas rotas em vez de 'id'.
     */
    public function getRouteKeyName()
    {
        return 'id_transacao';
    }

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }
}