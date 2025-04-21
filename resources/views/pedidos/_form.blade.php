<!-- Cabeçalho do Pedido -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Informações do Pedido</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">Cliente <span class="text-danger">*</span></label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Selecione o cliente</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}"
                                {{ old('cliente_id', $pedido->cliente_id ?? '') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">Vendedor <span class="text-danger">*</span></label>
                    <select name="vendedor_id" class="form-select @error('vendedor_id') is-invalid @enderror" required>
                        <option value="">Selecione o vendedor</option>
                        @foreach($vendedores as $vendedor)
                            <option value="{{ $vendedor->id }}"
                                {{ old('vendedor_id', $pedido->vendedor_id ?? '') == $vendedor->id ? 'selected' : '' }}>
                                {{ $vendedor->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendedor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label class="form-label">Data <span class="text-danger">*</span></label>
                    <input type="date" name="data" class="form-control @error('data') is-invalid @enderror"
                        value="{{ old('data', $pedido->data ?? date('Y-m-d')) }}" required>
                    @error('data')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Observações</label>
                    <textarea name="observacoes" class="form-control @error('observacoes') is-invalid @enderror" rows="3">{{ old('observacoes', $pedido->observacoes ?? '') }}</textarea>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Campo oculto para status -->
        <input type="hidden" name="status" value="{{ old('status', $pedido->status ?? 'Aberto') }}">
    </div>
</div>

<!-- Produtos do Pedido -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Produtos</h5>
        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarProduto">
            <i class="fas fa-plus-circle"></i> Adicionar Produto
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover" id="tabela-produtos">
                <thead class="table-light">
                    <tr>
                        <th>Produto</th>
                        <th style="width: 100px;">Qtd</th>
                        <th style="width: 180px;">Preço Unit. (R$)</th>
                        <th style="width: 120px;">Desconto (%)</th>
                        <th style="width: 180px;">Total (R$)</th>
                        <th style="width: 80px;">Ação</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="alert alert-info mt-3" id="sem-produtos" style="display: none;">
            <i class="fas fa-info-circle"></i> Nenhum produto adicionado. Clique em "Adicionar Produto" para incluir itens no pedido.
        </div>
    </div>
</div>

<!-- Resumo do Pedido -->
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Resumo</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <!-- Vazio para alinhamento -->
            </div>
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tr>
                            <td class="text-end fw-bold">Subtotal:</td>
                            <td class="text-end" style="width: 150px;">
                                R$ <span id="subtotal-geral">0,00</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Desconto dos itens:</td>
                            <td class="text-end text-danger">
                                -R$ <span id="desconto-itens">0,00</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Desconto adicional (%):</td>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="desconto_percentual" class="form-control form-control-sm"
                                           id="desconto-percentual" min="0" max="100" step="0.01"
                                           value="{{ old('desconto_percentual', $pedido->desconto_percentual ?? 0) }}"
                                           onchange="atualizarDesconto(this.value)">
                                    <span class="input-group-text">%</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">Valor do desconto adicional:</td>
                            <td class="text-end text-danger">
                                -R$ <span id="valor-desconto">0,00</span>
                                <input type="hidden" name="desconto_valor" id="desconto-valor-input"
                                       value="{{ old('desconto_valor', $pedido->desconto_valor ?? 0) }}">
                            </td>
                        </tr>
                        <tr class="table-active">
                            <td class="text-end fw-bold fs-5">Total:</td>
                            <td class="text-end fw-bold fs-5">
                                R$ <span id="total-geral">0,00</span>
                                <input type="hidden" name="valor_total" id="total-geral-input"
                                       value="{{ old('valor_total', $pedido->valor_total ?? 0) }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Adição de Produto -->
<div class="modal fade" id="modalAdicionarProduto" tabindex="-1" aria-labelledby="modalAdicionarProdutoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAdicionarProdutoLabel">Adicionar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="busca-produto" class="form-control" placeholder="Pesquisar produto por nome ou código...">
                </div>
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Produto</th>
                                <th>Código</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody id="lista-produtos">
                            <!-- Produtos serão adicionados aqui via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    let produtos = @json($produtos);
let itens = [];
let linhaIndex = 0;

    // Valores de totalização
    let subtotalGeral = 0;
    let descontoItens = 0;
    let descontoPercentual = {{ old('desconto_percentual', $pedido->desconto_percentual ?? 0) }};
    let descontoValor = 0;
    let totalGeral = 0;

    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function adicionarLinha(item = null) {
        document.getElementById('sem-produtos').style.display = 'none';

        const tabela = document.querySelector('#tabela-produtos tbody');
        const index = linhaIndex++;
        const linha = document.createElement('tr');

        // Produto - agora como campo oculto e texto visível
        const produtoNome = document.createElement('div');
        produtoNome.innerHTML = item ? item.produto.nome : 'Selecione um produto';

        const produtoInput = document.createElement('input');
        produtoInput.type = 'hidden';
        produtoInput.name = `produtos[${index}][produto_id]`;
        produtoInput.value = item ? item.produto_id : '';
        produtoInput.required = true;

        // Quantidade
        const quantidade = document.createElement('input');
        quantidade.type = 'number';
        quantidade.name = `produtos[${index}][quantidade]`;
        quantidade.classList.add('form-control');
        quantidade.required = true;
        quantidade.min = 1;
        quantidade.value = item ? item.quantidade : 1;

        // Preço Unitário
        const preco = document.createElement('input');
        preco.type = 'number';
        preco.name = `produtos[${index}][preco_unitario]`;
        preco.classList.add('form-control');
        preco.step = 0.01;
        preco.required = true;
        preco.value = item ? item.preco_unitario : 0;

        // Desconto por item
        const desconto = document.createElement('input');
        desconto.type = 'number';
        desconto.name = `produtos[${index}][desconto_percentual]`;
        desconto.classList.add('form-control');
        desconto.step = 0.01;
        desconto.min = 0;
        desconto.max = 100;
        desconto.value = item && item.desconto_percentual ? item.desconto_percentual : 0;

        // Total
        const total = document.createElement('span');
        total.classList.add('total-item');
        const totalValorBase = item ? (item.quantidade * item.preco_unitario) : 0;
        const descontoItem = item && item.desconto_percentual ? totalValorBase * (item.desconto_percentual / 100) : 0;
        const totalValorReal = totalValorBase - descontoItem;

        total.textContent = formatarMoeda(totalValorReal);

        // Botão remover
        const remover = document.createElement('button');
        remover.type = 'button';
        remover.classList.add('btn', 'btn-danger', 'btn-sm');
        remover.innerHTML = '<i class="fas fa-trash"></i>';
        remover.title = 'Remover item';
        remover.onclick = () => {
            linha.remove();
            atualizarTotais();

            // Mostrar alerta se não houver mais produtos
            if (document.querySelectorAll('#tabela-produtos tbody tr').length === 0) {
                document.getElementById('sem-produtos').style.display = 'block';
            }
        };

        // Eventos de atualização
        quantidade.oninput = atualizarLinha;
        preco.oninput = atualizarLinha;
        desconto.oninput = atualizarLinha;

        function atualizarLinha() {
            const subtotal = parseFloat(quantidade.value || 0) * parseFloat(preco.value || 0);
            const descontoPct = parseFloat(desconto.value || 0);
            const descontoValorItem = subtotal * (descontoPct / 100);
            const totalItem = subtotal - descontoValorItem;

            linha.dataset.subtotal = subtotal;
            linha.dataset.desconto = descontoValorItem;
            linha.dataset.total = totalItem;

            total.textContent = formatarMoeda(totalItem);
            atualizarTotais();
        }

        // Monta a linha
        const td1 = document.createElement('td');
        td1.appendChild(produtoNome);
        td1.appendChild(produtoInput);

        const td2 = document.createElement('td'); td2.appendChild(quantidade);
        const td3 = document.createElement('td'); td3.appendChild(preco);
        const td4 = document.createElement('td'); td4.appendChild(desconto);
        const td5 = document.createElement('td'); td5.classList.add('text-end'); td5.appendChild(total);
        const td6 = document.createElement('td'); td6.classList.add('text-center'); td6.appendChild(remover);

        linha.append(td1, td2, td3, td4, td5, td6);
        linha.dataset.subtotal = totalValorBase;
        linha.dataset.desconto = descontoItem;
        linha.dataset.total = totalValorReal;
        tabela.appendChild(linha);

        // Atualiza valores calculados
        atualizarTotais();
    }

    // Parte do script em _form.blade.php

function atualizarTotais() {
    // Calcula subtotal somando todos os itens (sem desconto)
    subtotalGeral = 0;
    descontoItens = 0;
    let totalItens = 0;

    document.querySelectorAll('#tabela-produtos tbody tr').forEach(tr => {
        const subtotalItem = parseFloat(tr.dataset.subtotal || 0);
        const descontoItem = parseFloat(tr.dataset.desconto || 0);
        const totalItem = subtotalItem - descontoItem;

        subtotalGeral += subtotalItem;
        descontoItens += descontoItem;
        totalItens += totalItem;
    });

    // Calcula desconto adicional e total
    descontoValor = totalItens * (descontoPercentual / 100);
    totalGeral = totalItens - descontoValor;

    // Atualiza os campos na tela
    document.getElementById('subtotal-geral').textContent = formatarMoeda(subtotalGeral);
    document.getElementById('desconto-itens').textContent = formatarMoeda(descontoItens);
    document.getElementById('valor-desconto').textContent = formatarMoeda(descontoValor);
    document.getElementById('total-geral').textContent = formatarMoeda(totalGeral);

    // Atualiza campos ocultos
    document.getElementById('desconto-valor-input').value = descontoValor.toFixed(2);
    document.getElementById('total-geral-input').value = totalGeral.toFixed(2);
}
    function atualizarDesconto(percentual) {
        descontoPercentual = parseFloat(percentual) || 0;
        descontoValor = (subtotalGeral - descontoItens) * descontoPercentual / 100;
        totalGeral = subtotalGeral - descontoItens - descontoValor;

        // Atualiza os campos na tela
        document.getElementById('subtotal-geral').textContent = formatarMoeda(subtotalGeral);
        document.getElementById('desconto-itens').textContent = formatarMoeda(descontoItens);
        document.getElementById('valor-desconto').textContent = formatarMoeda(descontoValor);
        document.getElementById('total-geral').textContent = formatarMoeda(totalGeral);

        // Atualiza campos ocultos
        document.getElementById('desconto-valor-input').value = descontoValor.toFixed(2);
        document.getElementById('total-geral-input').value = totalGeral.toFixed(2);
    }

    // Função para selecionar produto do modal
    function selecionarProduto(produtoId) {
        const produto = produtos.find(p => p.id == produtoId);
        if (!produto) return;

        // Criar o objeto de item
        const novoItem = {
            produto_id: produto.id,
            produto: produto, // Incluir objeto completo para referência
            quantidade: 1,
            preco_unitario: produto.preco,
            desconto_percentual: 0
        };

        // Adicionar linha
        adicionarLinha(novoItem);

        // Fechar o modal
        const modal = document.getElementById('modalAdicionarProduto');
        const modalInstance = bootstrap.Modal.getInstance(modal);
        modalInstance.hide();
    }

    // Configuração do campo de busca
    document.addEventListener('DOMContentLoaded', function() {
    const pedidoDataEl = document.getElementById('pedido-data');
    if (pedidoDataEl) {
        try {
            const pedidoItens = JSON.parse(pedidoDataEl.dataset.itens || '[]');
            if (pedidoItens && pedidoItens.length > 0) {
                console.log('Itens carregados:', pedidoItens);

                // Adiciona os itens ao formulário
                pedidoItens.forEach(item => {
                    adicionarLinha(item);
                });

                // Atualiza o total
                atualizarTotais();
            } else {
                // Se não tem itens, exibe a mensagem
                document.getElementById('sem-produtos').style.display = 'block';
                adicionarLinha(); // Linha vazia
            }
        } catch (error) {
            console.error('Erro ao carregar itens:', error);
            document.getElementById('sem-produtos').style.display = 'block';
            adicionarLinha(); // Linha vazia
        }
    } else {
        // Nova página (create)
        document.getElementById('sem-produtos').style.display = 'block';
        adicionarLinha(); // Linha vazia
    }
});

</script>
