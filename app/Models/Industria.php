<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Industria extends Model
{
    protected $fillable = [
        'nome',
        'razao_social',
        'cnpj',
        'inscricao_estadual',
        'email',
        'telefone',
        'website',
        'endereco',
        'comissao',
        'logo'
    ];

    /**
     * Relação com produtos
     */
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }
}
