@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Editar Produto: {{ $produto->nome }}</h2>
        <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('produtos._form', ['produto' => $produto])

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar Produto
                    </button>
                    <a href="{{ route('produtos.index') }}" class="btn btn-outline-danger">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
