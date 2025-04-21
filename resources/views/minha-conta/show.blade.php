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
                    <h5 class="mb-0">Detalhes da Indústria</h5>
                    <div>
                        <a href="{{ route('industrias.edit', $industria->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('industrias.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($industria->logo)
                                <img src="{{ asset('storage/' . $industria->logo) }}"
                                    alt="{{ $industria->nome }}" class="img-fluid mb-3"
                                    style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded p-5 mb-3">
                                    <i class="fas fa-industry fa-5x text-secondary"></i>
                                </div>
                            @endif

                            <h4>{{ $industria->nome }}</h4>
                            <p class="text-muted">{{ $industria->razao_social }}</p>

                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('industrias.produtos.importar', $industria->id) }}" class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Importar Produtos
                                </a>
                                <a href="{{ route('industrias.produtos.imagens', $industria->id) }}" class="btn btn-primary">
                                    <i class="fas fa-images"></i> Importar Imagens
                                </a>
                                <button class="btn btn-outline-primary" onclick="selecionarIndustria({{ $industria->id }})">
                                    <i class="fas fa-exchange-alt"></i> Selecionar como Atual
                                </button>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">Informações Básicas</div>
                                        <div class="card-body">
                                            <p><strong>CNPJ:</strong> {{ $industria->cnpj ?? 'Não informado' }}</p>
                                            <p><strong>Inscrição Estadual:</strong> {{ $industria->inscricao_estadual ?? 'Não informado' }}</p>
                                            <p><strong>Comissão:</strong> {{ number_format($industria->comissao, 2) }}%</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">Contato</div>
                                        <div class="card-body">
                                            <p><i class="fas fa-phone me-2 text-primary"></i> {{ $industria->telefone ?? 'Não informado' }}</p>
                                            <p><i class="fas fa-envelope me-2 text-primary"></i> {{ $industria->email ?? 'Não informado' }}</p>
                                            @if($industria->website)
                                                <p>
                                                    <i class="fas fa-globe me-2 text-primary"></i>
                                                    <a href="{{ $industria->website }}" target="_blank">{{ $industria->website }}</a>
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">Endereço</div>
                                        <div class="card-body">
                                            <p>{{ $industria->endereco ?? 'Endereço não informado' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <span>Produtos Cadastrados</span>
                                            <span class="badge bg-primary">{{ $produtos_count ?? 0 }}</span>
                                        </div>
                                        <div class="card-body">
                                            @if(($produtos_count ?? 0) > 0)
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="mb-0">Esta indústria possui {{ $produtos_count }} produtos cadastrados no sistema.</p>
                                                    <a href="{{ route('produtos.index') }}?industria_id={{ $industria->id }}"
                                                       class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-list"></i> Ver todos
                                                    </a>
                                                </div>
                                            @else
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                                    <p>Nenhum produto cadastrado para esta indústria.</p>
                                                    <a href="{{ route('industrias.produtos.importar', $industria->id) }}"
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-file-excel"></i> Importar Produtos
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
