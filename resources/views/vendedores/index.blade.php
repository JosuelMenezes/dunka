@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Vendedores</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('vendedores.create') }}" class="btn btn-primary mb-3">Novo Vendedor</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Comissão (%)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vendedores as $vendedor)
                <tr>
                    <td>{{ $vendedor->nome }}</td>
                    <td>{{ $vendedor->email }}</td>
                    <td>{{ $vendedor->telefone }}</td>
                    <td>{{ $vendedor->comissao }}</td>
                    <td>
                        <a href="{{ route('vendedores.edit', $vendedor->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('vendedores.destroy', $vendedor->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
