<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $fillable = [
        'nome',
        'codigo',
        'descricao',
        'foto',
        'variacao_ipi',
        'ean',
        'ncm',
        'preco',
        'estoque',
        'industria_id',
    ];

    public function industria()
    {
        return $this->belongsTo(Industria::class);
    }
}
