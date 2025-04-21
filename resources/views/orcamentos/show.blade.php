@extends('layouts.app')

@section('content')
<div class="container bg-white p-4 shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Orçamento #{{ $orcamento->id }}</h2>
        <button class="btn btn-outline-secondary" onclick="window.print()">🖨️ Imprimir</button>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <strong>Cliente:</strong> {{ $orcamento->cliente->nome }}<br>
            <strong>Telefone:</strong> {{ $orcamento->cliente->telefone }}<br>
            <strong>Email:</strong> {{ $orcamento->cliente->email }}
        </div>
        <div class="col-md-6 text-end">
            <strong>Vendedor:</strong> {{ $orcamento->vendedor->nome }}<br>
            <strong>Data:</strong> {{ \Carbon\Carbon::parse($orcamento->data)->format('d/m/Y') }}
        </div>
    </div>
    <a href="{{ route('orcamentos.pdf', $orcamento->id) }}" class="btn btn-outline-success">
        📄 Gerar PDF
    </a>

    <table class="table table-bordered mt-3">
        <thead class="table-light">
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeral = 0; @endphp
            @foreach($orcamento->itens as $item)
                @php $subtotal = $item->quantidade * $item->preco_unitario; $totalGeral += $subtotal; @endphp
                <tr>
                    <td>{{ $item->produto->nome }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th>R$ {{ number_format($totalGeral, 2, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    @if($orcamento->observacoes)
        <div class="mt-4">
            <strong>Observações:</strong><br>
            <p>{{ $orcamento->observacoes }}</p>
        </div>
    @endif
</div>

<style>
    @media print {
        .btn, nav, .topbar, .sidebar {
            display: none !important;
        }
        body {
            background-color: white;
        }
        .container {
            margin: 0;
            padding: 0;
            box-shadow: none;
        }
    }
</style>
@endsection
