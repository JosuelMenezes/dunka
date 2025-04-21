<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    protected $fillable = [
        'pedido_id',
        'produto_id',
        'quantidade',
        'preco_unitario',
        'desconto_percentual',
        'total'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }

    // Calcula o valor do desconto
    public function getDescontoValorAttribute()
    {
        $subtotal = $this->quantidade * $this->preco_unitario;
        return $subtotal * ($this->desconto_percentual / 100);
    }

    // Calcula o valor total com desconto
    public function getTotalComDescontoAttribute()
    {
        $subtotal = $this->quantidade * $this->preco_unitario;
        $desconto = $subtotal * ($this->desconto_percentual / 100);
        return $subtotal - $desconto;
    }
}
