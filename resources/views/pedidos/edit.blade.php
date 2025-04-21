@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Editar Pedido #{{ $pedido->id }}</h2>
        <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Esta div é importante para passar os dados ao JavaScript -->
    <div id="pedido-data"
         data-itens="{{ json_encode($pedido->itens->map(function($item) {
             return [
                 'id' => $item->id,
                 'produto_id' => $item->produto_id,
                 'quantidade' => $item->quantidade,
                 'preco_unitario' => $item->preco_unitario,
                 'desconto_percentual' => $item->desconto_percentual ?? 0,
                 'produto' => [
                     'id' => $item->produto->id,
                     'nome' => $item->produto->nome,
                     'codigo' => $item->produto->codigo ?? '',
                     'preco' => $item->produto->preco
                 ]
             ];
         })) }}"
    ></div>

    <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST">
        @csrf
        @method('PUT')

        @include('pedidos._form')

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-outline-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Atualizar Pedido
            </button>
        </div>
    </form>
</div>
@endsection
