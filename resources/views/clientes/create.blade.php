@extends('layouts.app')

@section('content')
<div class="container">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuração inicial
    toggleTipoPessoa('{{ old("tipo_pessoa", $cliente->tipo_pessoa ?? "F") }}');

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
