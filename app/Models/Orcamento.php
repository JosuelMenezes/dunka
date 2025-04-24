<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model
{
    protected $fillable = [
        'cliente_id',
        'vendedor_id',
        'data',
        'observacoes',
        'validade_proposta',
        'prazo_entrega',
        'condicoes_pagamento',
        'frete'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }

    public function itens()
    {
        return $this->hasMany(OrcamentoItem::class);
    }

    public function getTotalAttribute()
    {
        // Carregar os itens se ainda não estiverem carregados
        if (!$this->relationLoaded('itens')) {
            $this->load('itens');
        }

        return $this->itens->sum(function ($item) {
            return $item->quantidade * $item->preco_unitario;
        });
    }
}
