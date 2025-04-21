@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">
            Pedido #{{ $pedido->id }}
            @if($pedido->status == 'Cancelado')
                <span class="badge bg-danger">Cancelado</span>
            @elseif($pedido->status == 'Fechado')
                <span class="badge bg-success">Finalizado</span>
            @else
                <span class="badge bg-primary">Aberto</span>
            @endif
        </h2>
        <div>
            <a href="{{ route('pedidos.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <div class="btn-group ms-2">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-cog"></i> Ações
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a href="{{ route('pedidos.pdf', $pedido->id) }}" class="dropdown-item">
                            <i class="fas fa-file-pdf text-danger"></i> Gerar PDF
                        </a>
                    </li>

                    @if($pedido->status == 'Aberto')
                        <li>
                            <a href="{{ route('pedidos.edit', $pedido->id) }}" class="dropdown-item">
                                <i class="fas fa-edit text-warning"></i> Editar Pedido
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('pedidos.finalizar', $pedido->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item" onclick="return confirm('Deseja finalizar este pedido? Isso marcará como vendido e não poderá ser editado.')">
                                    <i class="fas fa-check-circle text-success"></i> Finalizar Pedido
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('pedidos.cancelar', $pedido->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item" onclick="return confirm('Deseja cancelar este pedido?')">
                                    <i class="fas fa-times-circle text-danger"></i> Cancelar Pedido
                                </button>
                            </form>
                        </li>
                    @endif

                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('pedidos.duplicar', $pedido->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item" onclick="return confirm('Deseja duplicar este pedido?')">
                                <i class="fas fa-copy text-primary"></i> Duplicar Pedido
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Alerta de status -->
    @if($pedido->status == 'Cancelado')
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-triangle"></i> Este pedido foi <strong>cancelado</strong> e não pode ser editado.
        </div>
    @elseif($pedido->status == 'Fechado')
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle"></i> Este pedido foi <strong>finalizado</strong> com sucesso.
        </div>
    @endif

    <!-- Informações do Pedido -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informações do Pedido</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 150px;">Data:</th>
                            <td>{{ \Carbon\Carbon::parse($pedido->data)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Cliente:</th>
                            <td>
                                <strong>{{ $pedido->cliente->nome }}</strong>
                                @if($pedido->cliente->telefone)
                                    <div><i class="fas fa-phone text-muted"></i> {{ $pedido->cliente->telefone }}</div>
                                @endif
                                @if($pedido->cliente->email)
                                    <div><i class="fas fa-envelope text-muted"></i> {{ $pedido->cliente->email }}</div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Vendedor:</th>
                            <td>{{ $pedido->vendedor->nome }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Observações</h5>
                </div>
                <div class="card-body">
                    @if($pedido->observacoes)
                        <p class="mb-0">{{ $pedido->observacoes }}</p>
                    @else
                        <p class="text-muted mb-0"><i>Nenhuma observação</i></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos do Pedido -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Produtos</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Qtd</th>
                            <th>Preço Unit.</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedido->itens as $item)
                            <tr>
                                <td>
                                    <div>{{ $item->produto->nome ?? 'Produto removido' }}</div>
                                    @if($item->produto && $item->produto->codigo)
                                        <small class="text-muted">Cód: {{ $item->produto->codigo }}</small>
                                    @endif
                                </td>
                                <td>{{ $item->quantidade }}</td>
                                <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                <td class="text-end">R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Resumo Financeiro -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- Área para informações adicionais, se necessário -->
                </div>
                <div class="col-md-6">
                   <!-- No show.blade.php, na parte do resumo financeiro -->
<table class="table table-sm">
    <tr>
        <td class="text-end fw-bold">Subtotal:</td>
        <td class="text-end" style="width: 150px;">
            R$ {{ number_format($pedido->getSubtotalAttribute(), 2, ',', '.') }}
        </td>
    </tr>

    @if($pedido->getDescontoItensAttribute() > 0)
        <tr>
            <td class="text-end fw-bold">Desconto nos itens:</td>
            <td class="text-end text-danger">
                -R$ {{ number_format($pedido->getDescontoItensAttribute(), 2, ',', '.') }}
            </td>
        </tr>
    @endif

    @if($pedido->desconto_percentual > 0)
        <tr>
            <td class="text-end fw-bold">Desconto adicional ({{ number_format($pedido->desconto_percentual, 2, ',', '.') }}%):</td>
            <td class="text-end text-danger">
                -R$ {{ number_format($pedido->desconto_valor, 2, ',', '.') }}
            </td>
        </tr>
    @endif

    <tr class="table-active">
        <td class="text-end fw-bold fs-5">Total:</td>
        <td class="text-end fw-bold fs-5">
            R$ {{ number_format($pedido->total, 2, ',', '.') }}
        </td>
    </tr>
</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
