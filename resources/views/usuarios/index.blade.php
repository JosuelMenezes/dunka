@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Usuários</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Novo Usuário</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Criado em</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
