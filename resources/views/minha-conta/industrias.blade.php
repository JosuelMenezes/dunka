@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Configurações</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Meu Perfil
                    </a>
                    <a href="{{ route('industrias.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-industry me-2"></i> Indústrias
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-palette me-2"></i> Aparência
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-shield-alt me-2"></i> Segurança
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-database me-2"></i> Backup
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Indústrias Representadas</h5>
                    <a href="{{ route('industrias.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nova Indústria
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @forelse($industrias as $industria)
                            <div class="col">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="d-flex justify-content-between p-3">
                                        <div class="d-flex flex-row align-items-center">
                                            <div class="icon">
                                                @if($industria->logo)
                                                    <img src="{{ asset('storage/' . $industria->logo) }}" class="img-fluid rounded"
                                                        style="width: 50px; height: 50px; object-fit: contain;" alt="{{ $industria->nome }}">
                                                @else
                                                    <i class="fas fa-industry" style="font-size: 2rem; color: #6c757d;"></i>
                                                @endif
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-0">{{ $industria->nome }}</h5>
                                                <span>{{ $industria->produtos_count ?? 0 }} produtos</span>
                                            </div>
                                        </div>

                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('industrias.edit', $industria->id) }}" class="dropdown-item">
                                                        <i class="fas fa-edit text-primary"></i> Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('industrias.show', $industria->id) }}" class="dropdown-item">
                                                        <i class="fas fa-eye text-info"></i> Detalhes
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('industrias.produtos.importar', $industria->id) }}" class="dropdown-item">
                                                        <i class="fas fa-file-excel text-success"></i> Importar Produtos
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('industrias.produtos.imagens', $industria->id) }}" class="dropdown-item">
                                                        <i class="fas fa-images text-warning"></i> Importar Imagens
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('industrias.destroy', $industria->id) }}" method="POST"
                                                          onsubmit="return confirm('Tem certeza que deseja excluir esta indústria?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash"></i> Excluir
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-at me-1"></i> {{ $industria->email ?? 'Sem e-mail' }}<br>
                                            <i class="fas fa-phone me-1"></i> {{ $industria->telefone ?? 'Sem telefone' }}
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Comissão:</span>
                                            <span class="badge bg-success">{{ number_format($industria->comissao, 2) }}%</span>
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button class="btn btn-outline-primary btn-sm" onclick="selecionarIndustria({{ $industria->id }})">
                                                <i class="fas fa-exchange-alt"></i> Selecionar
                                            </button>
                                            <a href="{{ route('produtos.index') }}?industria_id={{ $industria->id }}" class="btn btn-link btn-sm">
                                                Ver produtos <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-industry fa-3x mb-3"></i>
                                    <p>Nenhuma indústria cadastrada.</p>
                                    <a href="{{ route('industrias.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Cadastrar Indústria
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card-footer">
                    {{ $industrias->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<form id="form-selecionar-industria" action="{{ route('industria.selecionar', 0) }}" method="POST" style="display:none;">
    @csrf
</form>

@push('scripts')
<script>
    function selecionarIndustria(id) {
        const form = document.getElementById('form-selecionar-industria');
        form.action = form.action.replace(/\/\d+$/, '/' + id);
        form.submit();
    }
</script>
@endpush
@endsection
