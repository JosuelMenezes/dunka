@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Novo Vendedor</h2>

    <form action="{{ route('vendedores.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Comissão (%)</label>
            <input type="number" step="0.01" name="comissao" class="form-control" value="0">

        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('vendedores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
