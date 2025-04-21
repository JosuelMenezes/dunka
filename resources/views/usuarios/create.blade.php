@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Usuário</h1>

    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <label for="password">Senha</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password_confirmation">Confirmar Senha</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
