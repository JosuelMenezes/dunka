@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Indústria</h2>

    <form action="{{ route('industrias.update', $industria->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nome</label>
            <input type="text" name="nome" class="form-control" value="{{ $industria->nome }}" required>
        </div>

        <div class="mb-3">
            <label for="fantasy_name">Nome Fantasia</label>
            <input type="text" name="fantasy_name" class="form-control" value="{{ old('fantasy_name', $industria->fantasy_name ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="logo">Logo (Imagem)</label>
            <input type="file" name="logo" class="form-control">
            @if(!empty($industria->logo_path))
                <img src="{{ asset('storage/'.$industria->logo_path) }}" alt="Logo atual" class="mt-2" style="height: 60px;">
            @endif
        </div>


        <div class="mb-3">
            <label>CNPJ</label>
            <input type="text" name="cnpj" class="form-control" value="{{ $industria->cnpj }}">
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $industria->email }}">
        </div>

        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" value="{{ $industria->telefone }}">
        </div>

        <div class="mb-3">
            <label>Comissão (%)</label>
            <input type="number" step="0.01" name="comissao" class="form-control" value="{{ $industria->comissao }}">
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ route('industrias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
