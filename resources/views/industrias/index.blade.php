@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Indústrias</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('industrias.create') }}" class="btn btn-primary mb-3">Nova Indústria</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Comissão (%)</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($industrias as $industria)
                <tr>
                    <td>{{ $industria->nome }}</td>
                    <td>{{ $industria->cnpj }}</td>
                    <td>{{ $industria->email }}</td>
                    <td>{{ $industria->telefone }}</td>
                    <td>{{ $industria->comissao }}</td>
                    <td>
                        <a href="{{ route('industrias.edit', $industria->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('industrias.destroy', $industria->id) }}" method="POST" style="display:inline;">
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
