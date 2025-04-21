@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Formulário de Edição (Coluna Principal) -->
        <div class="col-md-9">
            <h2>{{ isset($cliente) ? 'Editar' : 'Novo' }} Cliente</h2>

            <form method="POST" action="{{ isset($cliente) ? route('clientes.update', $cliente->id) : route('clientes.store') }}">
                @csrf
                @if(isset($cliente))
                    @method('PUT')
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Dados Básicos</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1"
                                    {{ old('ativo', $cliente->ativo ?? 1) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">
                                    <span class="text-success" id="labelAtivo">Ativo</span>
                                    <span class="text-danger" id="labelInativo" style="display: none;">Inativo</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo de Pessoa</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_pessoa" id="tipo_pessoa_f" value="F"
                                    {{ old('tipo_pessoa', $cliente->tipo_pessoa ?? 'F') == 'F' ? 'checked' : '' }} onclick="toggleTipoPessoa('F')">
                                <label class="form-check-label" for="tipo_pessoa_f">Pessoa Física</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="tipo_pessoa" id="tipo_pessoa_j" value="J"
                                    {{ old('tipo_pessoa', $cliente->tipo_pessoa ?? '') == 'J' ? 'checked' : '' }} onclick="toggleTipoPessoa('J')">
                                <label class="form-check-label" for="tipo_pessoa_j">Pessoa Jurídica</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 pessoa-juridica">
                                <div class="mb-3">
                                    <label for="razao_social" class="form-label">Razão Social</label>
                                    <input type="text" class="form-control" id="razao_social" name="razao_social"
                                        value="{{ old('razao_social', $cliente->razao_social ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nome" class="form-label pessoa-fisica-label">Nome</label>
                                    <label for="nome" class="form-label pessoa-juridica-label">Nome Fantasia</label>
                                    <input type="text" class="form-control" id="nome" name="nome" required
                                        value="{{ old('nome', $cliente->nome ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="documento" class="form-label pessoa-fisica-label">CPF</label>
                                    <label for="documento" class="form-label pessoa-juridica-label">CNPJ</label>
                                    <input type="text" class="form-control" id="documento" name="documento"
                                        value="{{ old('documento', $cliente->documento ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6 pessoa-juridica">
                                <div class="mb-3">
                                    <label for="inscricao_estadual" class="form-label">Inscrição Estadual</label>
                                    <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual"
                                        value="{{ old('inscricao_estadual', $cliente->inscricao_estadual ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row pessoa-juridica">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="suframa" class="form-label">SUFRAMA</label>
                                    <input type="text" class="form-control" id="suframa" name="suframa"
                                        value="{{ old('suframa', $cliente->suframa ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Contato</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail Principal</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $cliente->email ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email_secundario" class="form-label">E-mail Secundário</label>
                                    <input type="email" class="form-control" id="email_secundario" name="email_secundario"
                                        value="{{ old('email_secundario', $cliente->email_secundario ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 pessoa-juridica">
                                <div class="mb-3">
                                    <label for="contato" class="form-label">Contato</label>
                                    <input type="text" class="form-control" id="contato" name="contato"
                                        placeholder="Nome da pessoa de contato"
                                        value="{{ old('contato', $cliente->contato ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone"
                                        value="{{ old('telefone', $cliente->telefone ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Endereço</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço Completo</label>
                            <textarea class="form-control" id="endereco" name="endereco" rows="3">{{ old('endereco', $cliente->endereco ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informações Adicionais</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="informacoes_adicionais" class="form-label">Observações</label>
                            <textarea class="form-control" id="informacoes_adicionais" name="informacoes_adicionais" rows="3">{{ old('informacoes_adicionais', $cliente->informacoes_adicionais ?? '') }}</textarea>
                            <div class="form-text">Adicione aqui quaisquer informações adicionais sobre este cliente.</div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>

        <!-- Resumo do Cliente (Coluna Lateral) -->
        @if(isset($cliente))
        <div class="col-md-3">
            <div class="card mb-4 sticky-top" style="top: 80px;">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Resumo</h5>
                    <span class="badge bg-light text-dark">Últimos 6 meses</span>
                </div>
                <div class="card-body">
                    @if(isset($cliente) && $cliente->ativo != 1)
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Cliente Inativo
                        <p class="small mb-0">Este cliente está inativo. Novos pedidos não podem ser criados.</p>
                    </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="circle-stat mx-auto d-flex align-items-center justify-content-center bg-light" style="width: 120px; height: 120px; border-radius: 50%;">
                            <div>
                                <h2 class="mb-0 text-primary">{{ $totalPedidos ?? 0 }}</h2>
                                <small>Pedidos</small>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center mb-4">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success">R$ {{ number_format($valorTotal ?? 0, 2, ',', '.') }}</h4>
                                <p class="small mb-0">Valor Total</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-warning">{{ $ultimoContato ?? '-' }}</h4>
                                <p class="small mb-0">Último Contato</p>
                            </div>
                        </div>
                    </div>

                    <h6>Histórico Recente</h6>
                    <ul class="list-group list-group-flush small">
                        @forelse($pedidosRecentes ?? [] as $pedido)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Pedido #{{ $pedido->id }}</span>
                                <span class="text-muted">{{ $pedido->data->format('d/m/Y') }}</span>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">Nenhum pedido recente</li>
                        @endforelse
                    </ul>

                    <div class="mt-3">
                        @if(isset($cliente) && $cliente->ativo == 1)
                            <a href="{{ route('pedidos.create', ['cliente_id' => $cliente->id]) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-plus"></i> Novo Pedido
                            </a>
                        @else
                            <button class="btn btn-outline-secondary btn-sm w-100" disabled>
                                <i class="fas fa-ban"></i> Cliente Inativo - Não pode criar pedidos
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuração inicial
    toggleTipoPessoa('{{ old("tipo_pessoa", $cliente->tipo_pessoa ?? "F") }}');

    // Configuração do toggle de status
    const checkboxAtivo = document.getElementById('ativo');
    const labelAtivo = document.getElementById('labelAtivo');
    const labelInativo = document.getElementById('labelInativo');

    function updateStatusLabel() {
        if (checkboxAtivo.checked) {
            labelAtivo.style.display = 'inline';
            labelInativo.style.display = 'none';
        } else {
            labelAtivo.style.display = 'none';
            labelInativo.style.display = 'inline';
        }
    }

    checkboxAtivo.addEventListener('change', updateStatusLabel);
    updateStatusLabel(); // Configuração inicial

    // Máscara para os campos (opcional, requer uma biblioteca como jQuery Mask)
    if (typeof $().mask === 'function') {
        $('#documento').mask('000.000.000-00');
        $('#telefone').mask('(00) 00000-0000');
    }
});

function toggleTipoPessoa(tipo) {
    if (tipo === 'F') {
        document.querySelectorAll('.pessoa-juridica').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.pessoa-fisica-label').forEach(el => el.style.display = 'block');
        document.querySelectorAll('.pessoa-juridica-label').forEach(el => el.style.display = 'none');
        document.getElementById('documento').placeholder = '000.000.000-00';
    } else {
        document.querySelectorAll('.pessoa-juridica').forEach(el => el.style.display = 'block');
        document.querySelectorAll('.pessoa-fisica-label').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.pessoa-juridica-label').forEach(el => el.style.display = 'block');
        document.getElementById('documento').placeholder = '00.000.000/0000-00';
    }
}
</script>
@endsection
