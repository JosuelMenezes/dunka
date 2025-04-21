@extends('layouts.app')

@section('content')
<div class="container">
    <h4>🔍 Resultados para: <strong>{{ $q }}</strong></h4>

    <hr>

    <h5>👥 Clientes</h5>
    @forelse($clientes as $cliente)
        <p>{{ $cliente->nome }}</p>
    @empty
        <p class="text-muted">Nenhum cliente encontrado.</p>
    @endforelse

    <h5>📦 Produtos</h5>
    @forelse($produtos as $produto)
        <p>{{ $produto->nome }}</p>
    @empty
        <p class="text-muted">Nenhum produto encontrado.</p>
    @endforelse

    <h5>🛒 Pedidos</h5>
    @forelse($pedidos as $pedido)
        <p>Pedido #{{ $pedido->id }} - Cliente: {{ $pedido->cliente->nome ?? 'N/A' }}</p>
    @empty
        <p class="text-muted">Nenhum pedido encontrado.</p>
    @endforelse
</div>
@endsection
