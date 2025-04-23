@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex align-items-center">
                    <i class="fas fa-cogs me-2"></i> <strong>Configurações do Sistema</strong>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('minha-conta.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nome_fantasia" class="form-label">Nome Fantasia</label>
                                <input type="text" name="nome_fantasia" class="form-control" value="{{ old('nome_fantasia', $config->nome_fantasia ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="cnpj" class="form-label">CNPJ</label>
                                <input type="text" name="cnpj" class="form-control" value="{{ old('cnpj', $config->cnpj ?? '') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ie" class="form-label">Inscrição Estadual</label>
                                <input type="text" name="ie" class="form-control" value="{{ old('ie', $config->ie ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $config->telefone ?? '') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $config->email ?? '') }}">
                        </div>

                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço Completo</label>
                            <textarea name="endereco" class="form-control" rows="2">{{ old('endereco', $config->endereco ?? '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="logomarca" class="form-label">Logomarca</label>
                            <input type="file" name="logomarca" class="form-control">
                            @if(isset($config->logomarca))
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $config->logomarca) }}" alt="Logomarca" class="img-thumbnail shadow-sm" style="max-height: 80px;">
                                </div>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Salvar Configurações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
