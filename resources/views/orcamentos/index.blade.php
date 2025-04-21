@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Orçamentos Cadastrados</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3 d-flex justify-content-between flex-wrap gap-2">
        <form action="{{ route('orcamentos.index') }}" method="GET" class="d-flex gap-2">
            <input type="text" name="busca" class="form-control" placeholder="Buscar por cliente ou vendedor" value="{{ request('busca') }}">
            <input type="date" name="data" class="form-control" value="{{ request('data') }}">

            <button class="btn btn-secondary">Filtrar</button>
        </form>
        <a href="{{ route('orcamentos.create') }}" class="btn btn-primary">+ Novo Orçamento</a>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Data</th>
                <th>Total</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orcamentos as $orcamento)
            <tr>
                <td>{{ $orcamento->id }}</td>
                <td>{{ $orcamento->cliente->nome ?? 'Removido' }}</td>
                <td>{{ $orcamento->vendedor->nome ?? 'Removido' }}</td>
                <td>{{ \Carbon\Carbon::parse($orcamento->data)->format('d/m/Y') }}</td>
                <td>R$ {{ number_format($orcamento->total, 2, ',', '.') }}</td>
                <td class="text-center">
                    <a href="{{ route('orcamentos.show', $orcamento->id) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('orcamentos.pdf', $orcamento->id) }}" class="btn btn-sm btn-outline-secondary">PDF</a>
                    <a href="{{ route('orcamentos.edit', $orcamento->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('orcamentos.destroy', $orcamento->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>

            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum orçamento encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
