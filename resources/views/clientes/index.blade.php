@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Listagem de Clientes (Coluna Principal) -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Clientes</h2>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">+ Novo Cliente</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Tipo</th>
                                    <th>Nome/Fantasia</th>
                                    <th>Razão Social</th>
                                    <th>CPF/CNPJ</th>
                                    <th>Contato</th>
                                    <th>E-mail</th>
                                    <th>Telefone</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clientes as $cliente)
                                    <tr>
                                        <td>{{ $cliente->id }}</td>
                <td>
                    @if($cliente->ativo == 1)
                        <span class="badge bg-success">Ativo</span>
                    @else
                        <span class="badge bg-danger">Inativo</span>
                    @endif

                </td>
                                        <td>{{ $cliente->id }}</td>
                                        <td>{{ $cliente->tipo_pessoa === 'F' ? 'Física' : 'Jurídica' }}</td>
                                        <td>{{ $cliente->nome }}</td>
                                        <td>{{ $cliente->razao_social }}</td>
                                        <td>{{ $cliente->documento }}</td>
                                        <td>{{ $cliente->contato }}</td>
                                        <td>{{ $cliente->email }}</td>
                                        <td>{{ $cliente->telefone }}</td>
                                        <td>
                                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Nenhum cliente cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Painel de Estatísticas (Coluna Lateral) -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Carteira de Clientes</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <canvas id="clientesChart" width="200" height="200"></canvas>
                    </div>

                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <h4 class="text-success">{{ $totalAtivos ?? 0 }}</h4>
                            <p class="small">Ativos</p>
                        </div>
                        <div>
                            <h4 class="text-danger">{{ $totalInativos ?? 0 }}</h4>
                            <p class="small">Inativos</p>
                        </div>
                        <div>
                            <h4 class="text-primary">{{ $novosClientes ?? 0 }}</h4>
                            <p class="small">Novos</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Resumo</h6>
                    <p><strong>Total de clientes:</strong> {{ $clientes->count() }}</p>
                    <p><strong>Clientes pessoa física:</strong> {{ $clientes->where('tipo_pessoa', 'F')->count() }}</p>
                    <p><strong>Clientes pessoa jurídica:</strong> {{ $clientes->where('tipo_pessoa', 'J')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('clientesChart').getContext('2d');
    const clientesChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Ativos', 'Inativos', 'Novos'],
            datasets: [{
                data: [{{ $totalAtivos ?? 0 }}, {{ $totalInativos ?? 0 }}, {{ $novosClientes ?? 0 }}],
                backgroundColor: ['#28a745', '#dc3545', '#007bff'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection
@endsection
