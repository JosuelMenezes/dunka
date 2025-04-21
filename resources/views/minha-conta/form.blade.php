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
                    <h5 class="mb-0">{{ isset($industria) ? 'Editar Indústria' : 'Nova Indústria' }}</h5>
                    <a href="{{ route('industrias.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ isset($industria) ? route('industrias.update', $industria->id) : route('industrias.store') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($industria))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Informações Básicas -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">Informações Básicas</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="razao_social" class="form-label">Razão Social *</label>
                                            <input type="text" class="form-control @error('razao_social') is-invalid @enderror"
                                                id="razao_social" name="razao_social"
                                                value="{{ old('razao_social', $industria->razao_social ?? '') }}" required>
                                            @error('razao_social')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="nome" class="form-label">Nome Fantasia *</label>
                                            <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                                id="nome" name="nome"
                                                value="{{ old('nome', $industria->nome ?? '') }}" required>
                                            @error('nome')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="cnpj" class="form-label">CNPJ</label>
                                            <input type="text" class="form-control @error('cnpj') is-invalid @enderror cnpj-mask"
                                                id="cnpj" name="cnpj"
                                                value="{{ old('cnpj', $industria->cnpj ?? '') }}">
                                            @error('cnpj')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="inscricao_estadual" class="form-label">Inscrição Estadual</label>
                                            <input type="text" class="form-control @error('inscricao_estadual') is-invalid @enderror"
                                                id="inscricao_estadual" name="inscricao_estadual"
                                                value="{{ old('inscricao_estadual', $industria->inscricao_estadual ?? '') }}">
                                            @error('inscricao_estadual')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Logomarca -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">Logomarca</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Logomarca</label>
                                            <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                                id="logo" name="logo" accept="image/*">
                                            @error('logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                            @if(isset($industria) && $industria->logo)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $industria->logo) }}"
                                                        alt="{{ $industria->nome }}" class="img-thumbnail"
                                                        style="max-height: 100px;">
                                                    <div class="form-text">Logo atual. Envie uma nova para substituir.</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Contato -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">Informações de Contato</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">Telefone</label>
                                            <input type="text" class="form-control @error('telefone') is-invalid @enderror telefone-mask"
                                                id="telefone" name="telefone"
                                                value="{{ old('telefone', $industria->telefone ?? '') }}">
                                            @error('telefone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email"
                                                value="{{ old('email', $industria->email ?? '') }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="website" class="form-label">Website</label>
                                            <input type="url" class="form-control @error('website') is-invalid @enderror"
                                                id="website" name="website"
                                                value="{{ old('website', $industria->website ?? '') }}">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Endereço -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">Endereço</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="endereco" class="form-label">Endereço Completo</label>
                                            <textarea class="form-control @error('endereco') is-invalid @enderror"
                                                id="endereco" name="endereco" rows="3">{{ old('endereco', $industria->endereco ?? '') }}</textarea>
                                            @error('endereco')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Comissão -->
                                <div class="card mb-4">
                                    <div class="card-header bg-light">Comissão</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="comissao" class="form-label">Percentual de Comissão (%)</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" min="0" max="100"
                                                    class="form-control @error('comissao') is-invalid @enderror"
                                                    id="comissao" name="comissao"
                                                    value="{{ old('comissao', $industria->comissao ?? 0) }}">
                                                <span class="input-group-text">%</span>
                                                @error('comissao')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <a href="{{ route('industrias.index') }}" class="btn btn-outline-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($industria) ? 'Atualizar' : 'Salvar' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Máscaras para os campos
    document.addEventListener('DOMContentLoaded', function() {
        // CNPJ Mask
        const cnpjInput = document.querySelector('.cnpj-mask');
        if (cnpjInput) {
            cnpjInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 14) value = value.slice(0, 14);

                if (value.length > 12) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/, '$1.$2.$3/$4-$5');
                } else if (value.length > 8) {
                    value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d*).*/, '$1.$2.$3/$4');
                } else if (value.length > 5) {
                    value = value.replace(/^(\d{2})(\d{3})(\d*).*/, '$1.$2.$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d*).*/, '$1.$2');
                }

                e.target.value = value;
            });
        }

        // Telefone Mask
        const telefoneInput = document.querySelector('.telefone-mask');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);

                if (value.length > 10) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
                } else if (value.length > 6) {
                    value = value.replace(/^(\d{2})(\d{4})(\d*).*/, '($1) $2-$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d*).*/, '($1) $2');
                }

                e.target.value = value;
            });
        }
    });
</script>
@endpush
@endsection
