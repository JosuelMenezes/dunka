@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Novo Orçamento</h2>

    <form method="POST" action="{{ route('orcamentos.store') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Cliente</label>
                <select name="cliente_id" class="form-control" required>
                    <option value="">Selecione</option>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Vendedor</label>
                <select name="vendedor_id" class="form-control" required>
                    <option value="">Selecione</option>
                    @foreach($vendedores as $vendedor)
                        <option value="{{ $vendedor->id }}">{{ $vendedor->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Data</label>
                <input type="date" name="data" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Observações</label>
            <textarea name="observacoes" class="form-control"></textarea>
        </div>

        <!-- Novos campos de condições comerciais -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Condições Comerciais</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Validade da Proposta</label>
                        <input type="text" name="validade_proposta" class="form-control" value="15 dias a partir da data de emissão">
                    </div>
                    <div class="col-md-6">
                        <label>Prazo de Entrega</label>
                        <input type="text" name="prazo_entrega" class="form-control" value="Conforme disponibilidade">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Condições de Pagamento</label>
                        <input type="text" name="condicoes_pagamento" class="form-control" value="A combinar">
                    </div>
                    <div class="col-md-6">
                        <label>Frete</label>
                        <input type="text" name="frete" class="form-control" value="Por conta do cliente">
                    </div>
                </div>
            </div>
        </div>

        <!-- Produtos -->
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h5 class="mb-0">Produtos</h5>
            </div>
            <div class="card-body">
                <!-- Busca de produtos super simples -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Buscar Produto</label>
                        <input type="text" id="filtro-produto" class="form-control" placeholder="Comece a digitar para filtrar...">
                    </div>
                    <div class="col-md-6">
                        <label>Selecionar Produto</label>
                        <select id="select-produto" class="form-control">
                            <option value="">Selecione um produto</option>
                            @foreach($produtos as $produto)
                                <option value="{{ $produto->id }}" data-preco="{{ $produto->preco }}">
                                    {{ $produto->nome }} {{ $produto->codigo ? '(Cód: '.$produto->codigo.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" id="adicionar-produto" class="btn btn-primary mt-2">
                            <i class="fas fa-plus"></i> Adicionar Produto
                        </button>
                    </div>
                </div>

                <!-- Tabela de produtos do orçamento -->
                <table class="table table-bordered" id="tabela-produtos">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th width="120px">Quantidade</th>
                            <th width="150px">Preço Unitário</th>
                            <th width="150px">Total</th>
                            <th width="80px">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Produtos adicionados aparecerão aqui -->
                    </tbody>
                </table>

                <div id="sem-produtos" class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> Nenhum produto adicionado. Selecione um produto acima e clique em "Adicionar Produto".
                </div>

                <div class="text-end mt-3">
                    <strong>Total do Orçamento: R$ <span id="total-geral">0.00</span></strong>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <a href="{{ route('orcamentos.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Salvar Orçamento
            </button>
        </div>
    </form>
</div>

<script>
    // Quando o documento estiver carregado
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos
        const filtroProduto = document.getElementById('filtro-produto');
        const selectProduto = document.getElementById('select-produto');
        const btnAdicionar = document.getElementById('adicionar-produto');
        const tabelaProdutos = document.querySelector('#tabela-produtos tbody');
        const semProdutos = document.getElementById('sem-produtos');

        let linhaIndex = 0;

        // Filtrar o select ao digitar
        filtroProduto.addEventListener('input', function() {
            const termo = this.value.toLowerCase();

            // Percorrer todas as opções e filtrar
            Array.from(selectProduto.options).forEach(option => {
                // Pular a primeira opção (placeholder)
                if (option.value === '') return;

                const texto = option.text.toLowerCase();
                const visible = texto.includes(termo);

                // Esconder/mostrar opções
                option.style.display = visible ? '' : 'none';
            });

            // Se tiver um único resultado visível, selecioná-lo
            const opcoesVisiveis = Array.from(selectProduto.options).filter(
                opt => opt.value !== '' && opt.style.display !== 'none'
            );

            if (opcoesVisiveis.length === 1) {
                opcoesVisiveis[0].selected = true;
            }
        });

        // Adicionar produto
        btnAdicionar.addEventListener('click', function() {
            if (!selectProduto.value) return;

            adicionarProdutoSelecionado();
        });

        // Função para adicionar o produto selecionado
        function adicionarProdutoSelecionado() {
            const selectedOption = selectProduto.options[selectProduto.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;

            const produtoId = selectedOption.value;
            const produtoNome = selectedOption.text;
            const produtoPreco = selectedOption.dataset.preco || 0;

            // Criar uma nova linha
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    ${produtoNome}
                    <input type="hidden" name="produtos[${linhaIndex}][produto_id]" value="${produtoId}">
                </td>
                <td>
                    <input type="number" name="produtos[${linhaIndex}][quantidade]"
                           class="form-control quantidade" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" name="produtos[${linhaIndex}][preco_unitario]"
                           class="form-control preco" value="${parseFloat(produtoPreco).toFixed(2)}"
                           step="0.01" required>
                </td>
                <td class="total">R$ ${parseFloat(produtoPreco).toFixed(2)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-remover">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            // Adicionar evento para calcular o subtotal quando mudar quantidade/preço
            const inputs = tr.querySelectorAll('input.quantidade, input.preco');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    const linha = this.closest('tr');
                    const qtd = parseFloat(linha.querySelector('input.quantidade').value) || 0;
                    const preco = parseFloat(linha.querySelector('input.preco').value) || 0;
                    const total = qtd * preco;

                    linha.querySelector('.total').textContent = 'R$ ' + total.toFixed(2);

                    // Recalcular total geral
                    calcularTotalGeral();
                });
            });

            // Adicionar evento para o botão remover
            tr.querySelector('.btn-remover').addEventListener('click', function() {
                tr.remove();

                // Atualizar visibilidade da mensagem "sem produtos"
                const temProdutos = tabelaProdutos.querySelectorAll('tr').length > 0;
                semProdutos.style.display = temProdutos ? 'none' : 'block';

                // Recalcular total
                calcularTotalGeral();
            });

            // Adicionar à tabela
            tabelaProdutos.appendChild(tr);
            linhaIndex++;

            // Esconder a mensagem "sem produtos"
            semProdutos.style.display = 'none';

            // Calcular total geral
            calcularTotalGeral();

            // Limpar seleção
            selectProduto.value = '';
            filtroProduto.value = '';

            // Mostrar todas as opções novamente
            Array.from(selectProduto.options).forEach(option => {
                option.style.display = '';
            });
        }

        // Função para calcular o total geral
        function calcularTotalGeral() {
            let total = 0;

            document.querySelectorAll('#tabela-produtos tbody .total').forEach(el => {
                const valor = parseFloat(el.textContent.replace('R$ ', '')) || 0;
                total += valor;
            });

            document.getElementById('total-geral').textContent = total.toFixed(2);
        }

        // Verificar se há produtos inicialmente
        const temProdutos = tabelaProdutos.querySelectorAll('tr').length > 0;
        semProdutos.style.display = temProdutos ? 'none' : 'block';
    });
</script>
@endsection
