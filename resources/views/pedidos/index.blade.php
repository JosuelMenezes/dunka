@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Pedidos</h2>
        <a href="{{ route('pedidos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Pedido
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtros e Busca -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('pedidos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="busca" class="form-control" placeholder="Buscar por cliente ou vendedor"
                               value="{{ request('busca') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Todos os status</option>
                        <option value="Aberto" {{ request('status') == 'Aberto' ? 'selected' : '' }}>Abertos</option>
                        <option value="Fechado" {{ request('status') == 'Fechado' ? 'selected' : '' }}>Finalizados</option>
                        <option value="Cancelado" {{ request('status') == 'Cancelado' ? 'selected' : '' }}>Cancelados</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="date" name="data" class="form-control" value="{{ request('data') }}" placeholder="Data">
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                        <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary" title="Limpar filtros">
                            <i class="fas fa-eraser"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de Pedidos -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td>
                                    <strong>#{{ $pedido->id }}</strong>
                                </td>
                                <td>{{ $pedido->cliente->nome ?? 'Cliente removido' }}</td>
                                <td>{{ $pedido->vendedor->nome ?? 'Vendedor removido' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pedido->data)->format('d/m/Y') }}</td>
                                <td>
                                    @if($pedido->status == 'Aberto')
                                        <span class="badge bg-primary">Aberto</span>
                                    @elseif($pedido->status == 'Fechado')
                                        <span class="badge bg-success">Finalizado</span>
                                    @else
                                        <span class="badge bg-danger">Cancelado</span>
                                    @endif
                                </td>
                                <td class="text-end">R$ {{ number_format($pedido->total, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-sm btn-info" title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($pedido->status == 'Aberto')
                                            <a href="{{ route('pedidos.edit', $pedido->id) }}" class="btn btn-sm btn-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('pedidos.pdf', $pedido->id) }}" class="dropdown-item">
                                                    <i class="fas fa-file-pdf text-danger"></i> Gerar PDF
                                                </a>
                                            </li>

                                            <li>
                                                <form action="{{ route('pedidos.duplicar', $pedido->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Deseja duplicar este pedido?')">
                                                        <i class="fas fa-copy text-primary"></i> Duplicar Pedido
                                                    </button>
                                                </form>
                                            </li>

                                            @if($pedido->status == 'Aberto')
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('pedidos.finalizar', $pedido->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Deseja finalizar este pedido? Isso marcará como vendido e não poderá ser editado.')">
                                                            <i class="fas fa-check-circle text-success"></i> Finalizar Pedido
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('pedidos.cancelar', $pedido->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item"
                                                                onclick="return confirm('Deseja cancelar este pedido?')">
                                                            <i class="fas fa-times-circle text-danger"></i> Cancelar Pedido
                                                        </button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Tem certeza que deseja excluir permanentemente este pedido? Esta ação não pode ser desfeita.')">
                                                            <i class="fas fa-trash-alt"></i> Excluir Permanentemente
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Nenhum pedido encontrado com os filtros selecionados.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Paginação -->
    <div class="d-flex justify-content-center mt-4">
        {{ $pedidos->withQueryString()->links() }}
    </div>
</div>

@section('scripts')
<script>
    // Adicionar FontAwesome para os ícones (adicione isso no layout principal ou use um CDN)
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.querySelector('[data-fa-link]')) {
            const link = document.createElement('link');
            link.setAttribute('rel', 'stylesheet');
            link.setAttribute('href', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
            link.setAttribute('data-fa-link', 'true');
            document.head.appendChild(link);
        }
    });
</script>
@endsection
@endsection
