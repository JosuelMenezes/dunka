<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Exportador principal do Dashboard – cria 4 abas: Resumo, Vendas, Produtos e Vendedores
 */
class DashboardExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new ResumoSheet($this->data['resumoMes']),
            new VendasSheet($this->data['vendasPorMes']),
            new ProdutosSheet($this->data['topProdutos']),
            new VendedoresSheet($this->data['vendasPorVendedor']),
        ];
    }
}

/** Aba "Resumo" – indicadores gerais */
class ResumoSheet implements FromArray, WithTitle
{
    protected $resumo;
    public function __construct($resumo) { $this->resumo = $resumo; }
    public function array(): array
    {
        return [
            ['Indicador', 'Valor'],
            ['Total de Pedidos', $this->resumo->total_pedidos],
            ['Valor Bruto', $this->resumo->bruto],
            ['Desconto', $this->resumo->desconto],
            ['Valor Líquido', $this->resumo->liquido],
        ];
    }
    public function title(): string { return 'Resumo'; }
}

/** Aba "Vendas" – evolução mensal */
class VendasSheet implements FromArray, WithTitle
{
    protected $vendas;
    public function __construct($vendasPorMes) { $this->vendas = $vendasPorMes; }
    public function array(): array
    {
        $rows = [['Mês', 'Valor (R$)']];
        foreach ($this->vendas as $registro) {
            $rows[] = [$registro->mes, $registro->valor];
        }
        return $rows;
    }
    public function title(): string { return 'Vendas'; }
}

/** Aba "Produtos" – Top 5 produtos no período */
class ProdutosSheet implements FromArray, WithTitle
{
    protected $produtos;
    public function __construct($topProdutos) { $this->produtos = $topProdutos; }
    public function array(): array
    {
        $rows = [['Produto', 'Qtd Vendida']];
        foreach ($this->produtos as $prod) {
            $rows[] = [$prod->nome, $prod->qtd];
        }
        return $rows;
    }
    public function title(): string { return 'Produtos'; }
}

/** Aba "Vendedores" – ranking de faturamento */
class VendedoresSheet implements FromArray, WithTitle
{
    protected $vendedores;
    public function __construct($vendasPorVendedor) { $this->vendedores = $vendasPorVendedor; }
    public function array(): array
    {
        $rows = [['Vendedor', 'Valor (R$)']];
        foreach ($this->vendedores as $vendedor) {
            $rows[] = [$vendedor->nome, $vendedor->valor];
        }
        return $rows;
    }
    public function title(): string { return 'Vendedores'; }
}
