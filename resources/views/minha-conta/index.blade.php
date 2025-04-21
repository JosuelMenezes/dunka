@extends('layouts.app')

@section('title', 'Minha Conta')

@section('content')
<div class="container">
    <h1 class="mb-4">Minha Conta</h1>

    <div class="alert alert-info">
        <strong>Bem-vindo!</strong> Selecione uma das opções no menu ao lado para configurar sua empresa ou o sistema.
    </div>

    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span><i class="fas fa-building me-2 text-primary"></i>Dados da Empresa</span>
            <a href="{{ route('minha-conta.empresa') }}" class="btn btn-outline-primary btn-sm">Acessar</a>
        </li>
        {{-- futuras opções como importar produtos, tema, backup... --}}
    </ul>
</div>
@endsection
