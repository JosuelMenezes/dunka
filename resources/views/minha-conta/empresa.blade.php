@extends('layouts.app')

@section('title', 'Empresa')

@section('content')
<div class="container">
    <h1>Dados da Empresa</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('minha-conta.empresa.salvar') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Razão Social *</label>
                <input type="text" name="razao_social" class="form-control" value="{{ old('razao_social', $industria->razao_social) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nome Fantasia</label>
                <input type="text" name="fantasia" class="form-control" value="{{ old('fantasia', $industria->fantasy_name) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">CNPJ</label>
                <input type="text" name="cnpj" class="form-control" value="{{ old('cnpj', $industria->cnpj) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Inscrição Estadual</label>
                <input type="text" name="ie" class="form-control" value="{{ old('ie', $industria->ie ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $industria->telefone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $industria->email) }}">
            </div>
            <div class="col-md-12">
                <label class="form-label">Endereço</label>
                <input type="text" name="endereco" class="form-control" value="{{ old('endereco', $industria->endereco ?? '') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Comissão (%)</label>
                <input type="number" step="0.01" name="comissao" class="form-control" value="{{ old('comissao', $industria->comissao) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Logomarca</label>
                <input type="file" name="logo" class="form-control">
                @if($industria->logo_path)
                    <img src="{{ $industria->logo_path }}" class="img-thumbnail mt-2" width="150">
                @endif
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
    </form>
</div>
@endsection
