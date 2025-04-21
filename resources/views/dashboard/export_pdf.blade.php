<h2>Resumo Dashboard</h2>

<p><strong>Pedidos:</strong> {{ $resumoMes->total_pedidos }}</p>
<p><strong>Bruto:</strong> R$ {{ number_format($resumoMes->bruto, 2, ',', '.') }}</p>
<p><strong>Desconto:</strong> R$ {{ number_format($resumoMes->desconto, 2, ',', '.') }}</p>
<p><strong>Líquido:</strong> R$ {{ number_format($resumoMes->liquido, 2, ',', '.') }}</p>

<hr>

<h4>Vendas por Mês</h4>
<ul>
    @foreach($vendasPorMes as $mes)
        <li>{{ $mes->mes }}: R$ {{ number_format($mes->valor, 2, ',', '.') }}</li>
    @endforeach
</ul>

<h4>Top Produtos</h4>
<ul>
    @foreach($topProdutos as $prod)
        <li>{{ $prod->nome }} ({{ $prod->qtd }})</li>
    @endforeach
</ul>

<h4>Ranking Vendedores</h4>
<ul>
    @foreach($vendasPorVendedor as $vendedor)
        <li>{{ $vendedor->nome }} — R$ {{ number_format($vendedor->valor, 2, ',', '.') }}</li>
    @endforeach
</ul>
