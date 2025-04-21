@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Vendedor</h2>

    <form action="{{ route('vendedores.update', $vendedor->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ $vendedor->nome }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $vendedor->email }}">
        </div>

        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" value="{{ $vendedor->telefone }}">
        </div>

        <div class="mb-3">
            <label>Comissão (%)</label>
            <input type="number" step="0.01" name="comissao" class="form-control" value="{{ $vendedor->comissao }}">
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('vendedores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
