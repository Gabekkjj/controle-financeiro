<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // -----------------------------------------------------------------
    // !! ADICIONE AS FUNÇÕES DE RELACIONAMENTO AQUI !!
    // -----------------------------------------------------------------

    /**
     * Relacionamento: Um usuário possui muitas transações.
     * (Este já devíamos ter)
     */
    public function transacoes()
    {
        return $this->hasMany(Transacao::class, 'id_usuario', 'id');
    }

    /**
     * Relacionamento: Um usuário possui muitos cofrinhos.
     * (Esta é a nova função do Passo 2)
     */
    public function cofrinhos()
    {
        return $this->hasMany(Cofrinho::class, 'id_usuario', 'id');
    }
}