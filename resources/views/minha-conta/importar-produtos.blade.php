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
                    <h5 class="mb-0">Importar Produtos - {{ $industria->nome }}</h5>
                    <a href="{{ route('industrias.show', $industria->id) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('warnings'))
                        <div class="alert alert-warning alert-dismissible fade show">
                            <h5><i class="fas fa-exclamation-circle"></i> Atenção</h5>
                            <p>Alguns problemas foram encontrados durante a importação:</p>
                            <ul>
                                @foreach(session('warnings') as $warning)
                                    <li>{{ $warning }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Instruções</h5>
                                </div>
                                <div class="card-body">
                                    <p>Para importar produtos, você precisa seguir o modelo de planilha abaixo:</p>

                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Campos necessários</h6>
                                        <ul class="mb-0">
                                            <li><strong>codigo:</strong> Código único do produto</li>
                                            <li><strong>nome:</strong> Nome do produto</li>
                                            <li><strong>preco:</strong> Preço (usar ponto como separador decimal)</li>
                                        </ul>
                                    </div>

                                    <div class="alert alert-secondary">
                                        <h6><i class="fas fa-list-ul"></i> Campos opcionais</h6>
                                        <ul class="mb-0">
                                            <li><strong>descricao:</strong> Descrição detalhada</li>
                                            <li><strong>ean:</strong> Código de barras EAN/GTIN</li>
                                            <li><strong>ncm:</strong> Código NCM</li>
                                            <li><strong>estoque:</strong> Quantidade em estoque</li>
                                            <li><strong>variacao_ipi:</strong> Percentual de IPI</li>
                                        </ul>
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <a href="{{ route('industrias.produtos.modelo', $industria->id) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-download"></i> Baixar Modelo de Planilha
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Upload de Produtos</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('industrias.produtos.processar', $industria->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="arquivo_excel" class="form-label">Arquivo Excel (.xlsx, .xls, .csv)</label>
                                            <input type="file" class="form-control @error('arquivo_excel') is-invalid @enderror"
                                                name="arquivo_excel" id="arquivo_excel"
                                                accept=".xlsx,.xls,.csv" required>
                                            @error('arquivo_excel')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="atualizar_existentes" name="atualizar_existentes" value="1" checked>
                                                <label class="form-check-label" for="atualizar_existentes">
                                                    Atualizar produtos existentes
                                                </label>
                                                <div class="form-text">
                                                    Se marcado, produtos com o mesmo código serão atualizados.
                                                    Caso contrário, serão ignorados.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="primeira_linha_cabecalho" name="primeira_linha_cabecalho" value="1" checked>
                                                <label class="form-check-label" for="primeira_linha_cabecalho">
                                                    Primeira linha contém cabeçalhos
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="sobrescrever_estoque" name="sobrescrever_estoque" value="1">
                                                <label class="form-check-label" for="sobrescrever_estoque">
                                                    Sobrescrever estoque existente
                                                </label>
                                                <div class="form-text">
                                                    Se marcado, o estoque será substituído pelo valor da planilha.
                                                    Caso contrário, os valores serão somados.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-upload"></i> Importar Produtos
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Produtos Cadastrados</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <strong>Total de produtos:</strong>
                                        <span class="badge bg-primary">{{ $produtos_count ?? 0 }}</span>
                                    </p>

                                    <div class="d-grid">
                                        <a href="{{ route('produtos.index') }}?industria_id={{ $industria->id }}"
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-list"></i> Ver Produtos
                                        </a>
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
@endsection
