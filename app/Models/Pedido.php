<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'cliente_id',
        'vendedor_id',
        'data',
        'status',
        'observacoes',
        'desconto_percentual',
        'desconto_valor',
        'valor_total'
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
        return $this->hasMany(PedidoItem::class);
    }

    // Método para calcular o subtotal (soma dos itens sem desconto)
    public function getSubtotalAttribute()
    {
        return $this->itens->sum(function ($item) {
            return $item->quantidade * $item->preco_unitario;
        });
    }

    // Método para calcular o total com desconto dos itens
    public function getTotalItensComDescontoAttribute()
    {
        return $this->itens->sum(function ($item) {
            $subtotal = $item->quantidade * $item->preco_unitario;
            $descontoItem = $subtotal * ($item->desconto_percentual / 100);
            return $subtotal - $descontoItem;
        });
    }

    // Método para calcular o desconto nos itens
    public function getDescontoItensAttribute()
    {
        return $this->getSubtotalAttribute() - $this->getTotalItensComDescontoAttribute();
    }

    // Método para calcular o total (inclui desconto por item e desconto geral)
    public function getTotalAttribute()
    {
        // Se já temos um valor total calculado no banco, use-o
        if (isset($this->attributes['valor_total']) && $this->attributes['valor_total'] > 0) {
            return $this->attributes['valor_total'];
        }

        // Caso contrário, calcule
        $totalComDescontoItens = $this->getTotalItensComDescontoAttribute();
        $descontoGeral = isset($this->attributes['desconto_valor']) ? $this->attributes['desconto_valor'] : 0;

        return $totalComDescontoItens - $descontoGeral;
    }

    // Método para calcular o desconto geral baseado no percentual
    public function calcularDescontoGeral()
    {
        $totalAposDescontoItens = $this->getTotalItensComDescontoAttribute();
        $percentual = isset($this->attributes['desconto_percentual']) ? $this->attributes['desconto_percentual'] : 0;

        return ($totalAposDescontoItens * $percentual) / 100;
    }

    // Método para atualizar o total e desconto
    public function atualizarTotais()
    {
        $this->desconto_valor = $this->calcularDescontoGeral();
        $this->valor_total = $this->getTotalItensComDescontoAttribute() - $this->desconto_valor;
        $this->save();

        return $this;
    }
}
