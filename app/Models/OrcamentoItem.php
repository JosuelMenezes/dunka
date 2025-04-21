<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrcamentoItem extends Model
{
    protected $fillable = ['orcamento_id', 'produto_id', 'quantidade', 'preco_unitario', 'total'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    public function orcamento()
    {
        return $this->belongsTo(Orcamento::class);
    }
}
