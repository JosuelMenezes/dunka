@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Orçamento #{{ $orcamento->id }}</h2>

    <form method="POST" action="{{ route('orcamentos.update', $orcamento->id) }}">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Cliente</label>
                <select name="cliente_id" class="form-control" required>
                    @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}" {{ $orcamento->cliente_id == $cliente->id ? 'selected' : '' }}>
                            {{ $cliente->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Vendedor</label>
                <select name="vendedor_id" class="form-control" required>
                    @foreach($vendedores as $vendedor)
                        <option value="{{ $vendedor->id }}" {{ $orcamento->vendedor_id == $vendedor->id ? 'selected' : '' }}>
                            {{ $vendedor->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Data</label>
                <input type="date" name="data" class="form-control" value="{{ $orcamento->data }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Observações</label>
            <textarea name="observacoes" class="form-control">{{ $orcamento->observacoes }}</textarea>
        </div>

        <h5>Produtos</h5>
        <table class="table table-bordered" id="tabela-produtos">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orcamento->itens as $key => $item)
                    <tr>
                        <td>
                            <select name="produtos[{{ $key }}][produto_id]" class="form-control" required>
                                <option value="">Selecione</option>
                                @foreach ($produtos as $produto)
                                    <option value="{{ $produto->id }}" {{ $item->produto_id == $produto->id ? 'selected' : '' }} data-preco="{{ $produto->preco }}">
                                        {{ $produto->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="number" name="produtos[{{ $key }}][quantidade]" class="form-control" value="{{ $item->quantidade }}" required></td>
                        <td><input type="number" step="0.01" name="produtos[{{ $key }}][preco_unitario]" class="form-control" value="{{ $item->preco_unitario }}" required></td>
                        <td><input type="text" class="form-control total-item" value="{{ number_format($item->quantidade * $item->preco_unitario, 2, '.', '') }}" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="this.closest('tr').remove(); atualizarTotalGeral();">Remover</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-success mb-3" onclick="adicionarLinha()">+ Adicionar Produto</button>

        <div class="text-end mb-3">
            <strong>Total do Orçamento: R$ <span id="total-geral">0.00</span></strong>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('orcamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    let produtos = @json($produtos);
    let linhaIndex = {{ count($orcamento->itens) }}; // Começamos a partir do próximo índice após os itens existentes

    function adicionarLinha() {
        const tabela = document.querySelector('#tabela-produtos tbody');
        const linha = document.createElement('tr');

        // Use o índice atual para nomear os campos
        const currentIndex = linhaIndex++;

        const select = document.createElement('select');
        select.name = 'produtos[' + currentIndex + '][produto_id]';
        select.classList.add('form-control');
        select.required = true;
        select.innerHTML = '<option value="">Selecione</option>';
        produtos.forEach(produto => {
            select.innerHTML += `<option value="${produto.id}" data-preco="${produto.preco}">${produto.nome}</option>`;
        });

        const quantidade = document.createElement('input');
        quantidade.type = 'number';
        quantidade.name = 'produtos[' + currentIndex + '][quantidade]';
        quantidade.classList.add('form-control');
        quantidade.required = true;
        quantidade.value = 1;

        const preco = document.createElement('input');
        preco.type = 'number';
        preco.name = 'produtos[' + currentIndex + '][preco_unitario]';
        preco.classList.add('form-control');
        preco.step = 0.01;
        preco.required = true;

        const total = document.createElement('input');
        total.type = 'text';
        total.classList.add('form-control', 'total-item');
        total.readOnly = true;
        total.value = '0.00';

        const remover = document.createElement('button');
        remover.type = 'button';
        remover.classList.add('btn', 'btn-danger', 'btn-sm');
        remover.innerText = 'Remover';
        remover.onclick = () => { linha.remove(); atualizarTotalGeral(); };

        select.onchange = () => {
            const precoSelecionado = select.selectedOptions[0].dataset.preco;
            preco.value = precoSelecionado || 0;
            atualizarLinha();
        };

        quantidade.oninput = atualizarLinha;
        preco.oninput = atualizarLinha;

        function atualizarLinha() {
            const subtotal = parseFloat(quantidade.value) * parseFloat(preco.value);
            total.value = subtotal.toFixed(2);
            atualizarTotalGeral();
        }

        linha.appendChild(document.createElement('td')).appendChild(select);
        linha.appendChild(document.createElement('td')).appendChild(quantidade);
        linha.appendChild(document.createElement('td')).appendChild(preco);
        linha.appendChild(document.createElement('td')).appendChild(total);
        linha.appendChild(document.createElement('td')).appendChild(remover);

        tabela.appendChild(linha);
    }

    function atualizarTotalGeral() {
        let total = 0;
        document.querySelectorAll('.total-item').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total-geral').innerText = total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', atualizarTotalGeral);
</script>
@endsection
