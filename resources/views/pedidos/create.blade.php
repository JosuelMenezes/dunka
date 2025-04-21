@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Novo Pedido</h2>
        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <form action="{{ route('pedidos.store') }}" method="POST">
        @csrf

        @include('pedidos._form')

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar Pedido
            </button>
        </div>
    </form>
</div>
@endsection
