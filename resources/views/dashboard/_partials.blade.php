<div class="row g-3">
    <div class="col-md-3">
        <div class="card card-stat shadow-sm text-center p-3">
            <h6 class="text-muted">Pedidos no período</h6>
            <h2>{{ $resumoMes->total_pedidos }}</h2>
        </div>
    </div>

{{-- === CARD META GERAL === --}}
@if($metaGeral)
    @php
        $atingido = $resumoMes->liquido ?? 0;
        $percent  = $metaGeral->valor_alvo > 0
                    ? round(($atingido / $metaGeral->valor_alvo) * 100)
                    : 0;
    @endphp

    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-2">Meta de Vendas ({{ \Carbon\Carbon::parse($metaGeral->inicio)->format('d/m') }}
                – {{ \Carbon\Carbon::parse($metaGeral->fim)->format('d/m') }})</h5>

            <div class="progress mb-2" style="height: 25px;">
                <div class="progress-bar @if($percent>=100) bg-success @endif"
                     role="progressbar"
                     style="width: {{ $percent }}%;">
                    {{ $percent }}%
                </div>
            </div>
            <small>Atingido: R$ {{ number_format($atingido,2,',','.') }}
                / Meta: R$ {{ number_format($metaGeral->valor_alvo,2,',','.') }}</small>
        </div>
    </div>
@endif

{{-- === RANKING CLIENTES === --}}
<div class="card mt-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title mb-3">Top 5 Clientes</h5>
        <ul class="list-group">
            @foreach($rankingClientes as $cli)
                <li class="list-group-item d-flex justify-content-between">
                    {{ $cli->nome }}
                    <span class="badge bg-secondary">
                        R$ {{ number_format($cli->total,2,',','.') }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>



    <div class="col-md-3">
        <div class="card card-stat shadow-sm text-center p-3">
            <h6 class="text-muted">Bruto</h6>
            <h2>R$ {{ number_format($resumoMes->bruto, 2, ',', '.') }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm text-center p-3">
            <h6 class="text-muted">Desconto</h6>
            <h2>R$ {{ number_format($resumoMes->desconto, 2, ',', '.') }}</h2>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm text-center p-3">
            <h6 class="text-muted">Líquido</h6>
            <h2 class="text-success">R$ {{ number_format($resumoMes->liquido, 2, ',', '.') }}</h2>
        </div>
    </div>
</div>

<div class="card mt-4 shadow-sm">
    <div class="card-body">
        <h5 class="card-title">Evolução de Vendas</h5>
        <canvas id="chartVendas"></canvas>
    </div>
</div>

<div class="row mt-4 g-3">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Top 5 Produtos</h5>
                <ul class="list-group">
                    @foreach($topProdutos as $produto)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $produto->nome }}
                            <span class="badge bg-primary">{{ $produto->qtd }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Ranking de Vendedores</h5>
                <canvas id="chartVendedor"></canvas>
            </div>
        </div>
    </div>
</div>
