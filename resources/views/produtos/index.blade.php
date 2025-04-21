@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Produtos Cadastrados</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('produtos.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, código, EAN, NCM..."
                        value="{{ request('busca') }}">
                </div>
                <div class="col-md-3">
                    <select name="industria_id" class="form-control">
                        <option value="">Todas as indústrias</option>
                        @foreach($industrias as $industria)
                            <option value="{{ $industria->id }}" {{ request('industria_id') == $industria->id ? 'selected' : '' }}>
                                {{ $industria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
                    <a href="{{ route('produtos.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-eraser"></i> Limpar
                    </a>
                    <a href="{{ route('produtos.create') }}" class="btn btn-success ms-auto">
                        <i class="fas fa-plus"></i> Novo Produto
                    </a>
                    <a href="{{ route('produtos.downloadPdf') }}?{{ http_build_query(request()->all()) }}"
                        class="btn btn-outline-danger">
                        <i class="far fa-file-pdf"></i> Exportar PDF
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de produtos -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th width="80">Foto</th>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Indústria</th>
                            <th>Preço</th>
                            <th>IPI (%)</th>
                            <th>Estoque</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produtos as $produto)
                            <tr>
                                <td>
                                    @if($produto->foto)
                                        <img src="{{ asset('storage/produtos/' . $produto->foto) }}"
                                            alt="{{ $produto->nome }}" class="img-thumbnail" style="height: 50px;">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}"
                                            alt="Sem imagem" class="img-thumbnail" style="height: 50px;">
                                    @endif
                                </td>
                                <td>{{ $produto->codigo }}</td>
                                <td>
                                    <strong>{{ $produto->nome }}</strong>
                                    @if($produto->descricao)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($produto->descricao, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $produto->industria->nome ?? 'N/A' }}</td>
                                <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                                <td>{{ $produto->variacao_ipi }}%</td>
                                <td>{{ $produto->estoque }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-info"
                                            data-bs-toggle="modal" data-bs-target="#modalProduto{{ $produto->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Modal de Detalhes -->
                                    <div class="modal fade" id="modalProduto{{ $produto->id }}" tabindex="-1"
                                        aria-labelledby="modalProdutoLabel{{ $produto->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalProdutoLabel{{ $produto->id }}">
                                                        Detalhes do Produto
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4 text-center">
                                                            @if($produto->foto)
                                                                <img src="{{ asset('storage/produtos/' . $produto->foto) }}"
                                                                    alt="{{ $produto->nome }}" class="img-fluid img-thumbnail mb-3">
                                                            @else
                                                                <img src="{{ asset('images/no-image.png') }}"
                                                                    alt="Sem imagem" class="img-fluid img-thumbnail mb-3">
                                                            @endif
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h4>{{ $produto->nome }}</h4>
                                                            <p><strong>Código:</strong> {{ $produto->codigo ?: 'N/A' }}</p>
                                                            <p><strong>Indústria:</strong> {{ $produto->industria->nome }}</p>
                                                            <p><strong>EAN/GTIN:</strong> {{ $produto->ean ?: 'N/A' }}</p>
                                                            <p><strong>NCM:</strong> {{ $produto->ncm ?: 'N/A' }}</p>
                                                            <p><strong>Preço:</strong> R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                                                            <p><strong>Variação IPI:</strong> {{ $produto->variacao_ipi }}%</p>
                                                            <p><strong>Estoque:</strong> {{ $produto->estoque }} unidades</p>
                                                        </div>
                                                    </div>
                                                    @if($produto->descricao)
                                                        <hr>
                                                        <h5>Descrição</h5>
                                                        <p>{{ $produto->descricao }}</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                    <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-primary">Editar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Nenhum produto encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-4">
                {{ $produtos->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

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
