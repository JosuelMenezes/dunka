@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    {{-- FILTROS DE DATA --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="filtro_periodo" class="form-label">Período:</label>
            <input type="text" class="form-control" id="filtro_periodo" name="periodo" />
        </div>
    </div>

    {{-- FILTROS DE INDÚSTRIA E PRODUTO --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <label for="filtro_industria" class="form-label">Indústria:</label>
            <select class="form-select" id="filtro_industria" name="industria_id">
                <option value="">Todas</option>
                @foreach($industrias as $industria)
                    <option value="{{ $industria->id }}">{{ $industria->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label for="filtro_produto" class="form-label">Produto:</label>
            <select class="form-select" id="filtro_produto" name="produto_id">
                <option value="">Todos</option>
                @foreach($produtos as $produto)
                    <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-3 gap-2">
        <a id="btnExportPdf" href="#" class="btn btn-outline-danger btn-sm">
            <i class="fas fa-file-pdf"></i> Exportar PDF
        </a>
        <a id="btnExportExcel" href="#" class="btn btn-outline-success btn-sm">
            <i class="fas fa-file-excel"></i> Exportar Excel
        </a>
    </div>


    <div id="dashboard-dinamico"></div>
</div>
@endsection

@push('scripts')
@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
let chartVendas = null;
let chartVendedor = null;

function carregarDashboard(periodo = null) {
    const industria_id = $('#filtro_industria').val();
    const produto_id   = $('#filtro_produto').val();

    $.get("{{ route('dashboard.data') }}", {
        periodo,
        industria_id,
        produto_id
    }, function(res) {
        $('#dashboard-dinamico').html(res.html);

        // Gráfico de vendas
        if (chartVendas) chartVendas.destroy();
        chartVendas = new Chart(document.getElementById('chartVendas'), {
            type: 'line',
            data: {
                labels: res.labelsMes,
                datasets: [{
                    label: 'Vendas (R$)',
                    data: res.valoresMes,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Gráfico de vendedores
        if (chartVendedor) chartVendedor.destroy();
        chartVendedor = new Chart(document.getElementById('chartVendedor'), {
            type: 'bar',
            data: {
                labels: res.labelsVend,
                datasets: [{
                    label: 'R$',
                    data: res.valoresVend
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });
    });
}

// Inicialização dos filtros
$('#filtro_periodo').daterangepicker({
    locale: {
        format: 'DD/MM/YYYY',
        applyLabel: "Aplicar",
        cancelLabel: "Cancelar",
        daysOfWeek: ["Dom","Seg","Ter","Qua","Qui","Sex","Sab"],
        monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
    }
}, function(start, end) {
    carregarDashboard(start.format('YYYY-MM-DD') + '|' + end.format('YYYY-MM-DD'));
});

$('#filtro_industria, #filtro_produto').on('change', function() {
    const periodo = $('#filtro_periodo').val();
    carregarDashboard(periodo);
});

// Carregamento inicial
carregarDashboard();

function montarURLExport(base) {
    const periodo = $('#filtro_periodo').val();
    const industria_id = $('#filtro_industria').val();
    const produto_id = $('#filtro_produto').val();

    const params = new URLSearchParams();
    if (periodo) params.append('periodo', periodo);
    if (industria_id) params.append('industria_id', industria_id);
    if (produto_id) params.append('produto_id', produto_id);

    return `${base}?${params.toString()}`;
}

$('#btnExportPdf').on('click', function(e) {
    e.preventDefault();
    window.open(montarURLExport("{{ route('dashboard.export.pdf') }}"), '_blank');
});

$('#btnExportExcel').on('click', function(e) {
    e.preventDefault();
    window.open(montarURLExport("{{ route('dashboard.export.excel') }}"), '_blank');
});


</script>
@endpush
