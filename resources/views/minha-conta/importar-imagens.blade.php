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
                    <h5 class="mb-0">Importar Imagens - {{ $industria->nome }}</h5>
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
                            <p>Alguns problemas foram encontrados durante a importação de imagens:</p>
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
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle"></i> Como funciona</h6>
                                        <p>O sistema associará as imagens aos produtos pelo nome do arquivo:</p>
                                        <ul class="mb-0">
                                            <li>O nome do arquivo deve ser o <strong>código do produto</strong></li>
                                            <li>Ex: se o código do produto for <strong>ABC123</strong>, o nome do arquivo deve ser <strong>ABC123.jpg</strong> (ou png, jpeg)</li>
                                            <li>Arquivos aceitos: JPG, PNG, JPEG</li>
                                            <li>Tamanho máximo por arquivo: 2MB</li>
                                        </ul>
                                    </div>

                                    <div class="alert alert-warning">
                                        <h6><i class="fas fa-exclamation-triangle"></i> Importante</h6>
                                        <ul class="mb-0">
                                            <li>Imagens com nomes que não correspondem a códigos de produtos serão ignoradas</li>
                                            <li>Se um produto já tiver imagem, ela será substituída</li>
                                            <li>O sistema redimensionará automaticamente imagens muito grandes</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Produtos Sem Imagem</h5>
                                </div>
                                <div class="card-body">
                                    <p>Produtos desta indústria que ainda não possuem imagem:</p>

                                    @if(($produtos_sem_imagem ?? 0) > 0)
                                        <div class="alert alert-secondary">
                                            <p class="mb-1">Existem <strong>{{ $produtos_sem_imagem }}</strong> produtos sem imagem.</p>
                                            <small>Você pode baixar a lista para ver os códigos dos produtos.</small>
                                        </div>

                                        <div class="d-grid">
                                            <a href="{{ route('industrias.produtos.sem-imagem', $industria->id) }}"
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download"></i> Baixar Lista de Produtos Sem Imagem
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i> Todos os produtos desta indústria possuem imagens.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Upload de Imagens</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('industrias.produtos.processar-imagens', $industria->id) }}"
                                          method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="imagens" class="form-label">Selecione as imagens</label>
                                            <input type="file" class="form-control @error('imagens') is-invalid @enderror"
                                                name="imagens[]" id="imagens" multiple
                                                accept=".jpg,.jpeg,.png" required>
                                            <div class="form-text">
                                                Você pode selecionar várias imagens de uma vez.
                                            </div>
                                            @error('imagens')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="redimensionar" name="redimensionar" value="1" checked>
                                                <label class="form-check-label" for="redimensionar">
                                                    Redimensionar imagens automaticamente
                                                </label>
                                                <div class="form-text">
                                                    Imagens serão redimensionadas para um tamanho adequado, preservando a qualidade.
                                                </div>
                                            </div>
                                        </div>

                                        <div class="progress mb-3" style="display: none;" id="progress-container">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                 role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success" id="btn-upload">
                                                <i class="fas fa-upload"></i> Fazer Upload
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Resultado do Upload</h5>
                                </div>
                                <div class="card-body" id="resultado-upload">
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-images fa-3x mb-3"></i>
                                        <p>Nenhum upload realizado recentemente.</p>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const progressContainer = document.getElementById('progress-container');
        const progressBar = progressContainer.querySelector('.progress-bar');
        const btnUpload = document.getElementById('btn-upload');
        const inputImagens = document.getElementById('imagens');
        const resultadoUpload = document.getElementById('resultado-upload');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!inputImagens.files.length) {
                alert('Por favor, selecione pelo menos uma imagem.');
                return;
            }

            // Mostrar progresso
            progressContainer.style.display = 'block';
            btnUpload.disabled = true;
            btnUpload.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';

            // Preparar FormData
            const formData = new FormData(form);

            // Enviar requisição AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            // Atualizar progresso
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                    progressBar.setAttribute('aria-valuenow', percentComplete);
                }
            });

            // Quando concluído
            xhr.onload = function() {
                btnUpload.disabled = false;

                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            btnUpload.innerHTML = '<i class="fas fa-check-circle"></i> Concluído';
                            progressBar.classList.remove('progress-bar-animated');
                            progressBar.classList.add('bg-success');

                            // Exibir resultado
                            let html = `
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Upload concluído com sucesso!
                                </div>
                                <div class="mb-3">
                                    <p><strong>Resumo:</strong></p>
                                    <ul>
                                        <li>Imagens enviadas: ${response.total}</li>
                                        <li>Imagens processadas: ${response.processadas}</li>
                                        <li>Produtos atualizados: ${response.atualizados}</li>
                                    </ul>
                                </div>
                            `;

                            if (response.ignoradas && response.ignoradas.length > 0) {
                                html += `
                                    <div class="alert alert-warning">
                                        <p><strong>Imagens ignoradas:</strong></p>
                                        <ul>
                                `;

                                response.ignoradas.forEach(item => {
                                    html += `<li>${item.arquivo}: ${item.motivo}</li>`;
                                });

                                html += `
                                        </ul>
                                    </div>
                                `;
                            }

                            resultadoUpload.innerHTML = html;

                            // Resetar form após 3 segundos
                            setTimeout(() => {
                                progressBar.style.width = '0%';
                                progressBar.setAttribute('aria-valuenow', 0);
                                progressBar.classList.add('progress-bar-animated');
                                progressBar.classList.remove('bg-success');
                                progressContainer.style.display = 'none';
                                btnUpload.innerHTML = '<i class="fas fa-upload"></i> Fazer Upload';
                                form.reset();
                            }, 3000);

                        } else {
                            btnUpload.innerHTML = '<i class="fas fa-upload"></i> Tentar Novamente';
                            progressBar.classList.add('bg-danger');

                            resultadoUpload.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle"></i> Erro: ${response.message}
                                </div>
                            `;
                        }
                    } catch (e) {
                        btnUpload.innerHTML = '<i class="fas fa-upload"></i> Tentar Novamente';
                        progressBar.classList.add('bg-danger');

                        resultadoUpload.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> Erro ao processar resposta do servidor.
                            </div>
                        `;
                    }
                } else {
                    btnUpload.innerHTML = '<i class="fas fa-upload"></i> Tentar Novamente';
                    progressBar.classList.add('bg-danger');

                    resultadoUpload.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Erro ${xhr.status}: ${xhr.statusText}
                        </div>
                    `;
                }
            };

            // Em caso de erro
            xhr.onerror = function() {
                btnUpload.disabled = false;
                btnUpload.innerHTML = '<i class="fas fa-upload"></i> Tentar Novamente';
                progressBar.classList.add('bg-danger');

                resultadoUpload.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> Erro de conexão. Verifique sua internet.
                    </div>
                `;
            };

            xhr.send(formData);
        });

        // Preview das imagens selecionadas
        inputImagens.addEventListener('change', function() {
            if (this.files.length > 0) {
                let html = `
                    <div class="mb-3">
                        <p><strong>${this.files.length} imagens selecionadas:</strong></p>
                        <div class="row g-2">
                `;

                // Limitar a exibição a 12 imagens para não sobrecarregar
                const maxPreview = Math.min(this.files.length, 12);

                for (let i = 0; i < maxPreview; i++) {
                    const file = this.files[i];
                    const fileExt = file.name.split('.').pop().toLowerCase();

                    // Verificar se é uma imagem válida
                    if (['jpg', 'jpeg', 'png'].includes(fileExt)) {
                        // Extrair o código do produto do nome do arquivo
                        const codigo = file.name.replace('.' + fileExt, '');

                        html += `
                            <div class="col-4 col-md-3">
                                <div class="card">
                                    <div class="card-img-top bg-light text-center" style="height: 100px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                    <div class="card-body p-2">
                                        <p class="card-text small text-truncate" title="${file.name}">
                                            ${file.name}
                                        </p>
                                        <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }

                if (this.files.length > maxPreview) {
                    html += `
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> E mais ${this.files.length - maxPreview}
                                imagens não exibidas na pré-visualização.
                            </div>
                        </div>
                    `;
                }

                html += `
                        </div>
                    </div>
                `;

                resultadoUpload.innerHTML = html;
            }
        });
    });
</script>
@endpush
@endsection
