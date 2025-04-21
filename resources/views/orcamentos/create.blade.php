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

        <h5>Produtos</h5>
        <table class="table table-bordered" id="tabela-produtos">
            <thead class="table-light">
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button type="button" class="btn btn-sm btn-success mb-3" onclick="adicionarLinha()">+ Adicionar Produto</button>

        <div class="mb-3 text-end">
            <strong>Total do Orçamento: R$ <span id="total-geral">0.00</span></strong>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Orçamento</button>
        <a href="{{ route('orcamentos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
    let produtos = @json($produtos);

    function adicionarLinha() {
        const tabela = document.querySelector('#tabela-produtos tbody');
        const linha = document.createElement('tr');

        // Produto
        const select = document.createElement('select');
        select.name = 'produtos[][produto_id]';
        select.classList.add('form-control');
        select.required = true;
        select.innerHTML = '<option value="">Selecione</option>';
        produtos.forEach(produto => {
            select.innerHTML += `<option value="${produto.id}" data-preco="${produto.preco}">${produto.nome}</option>`;
        });

        // Quantidade
        const quantidade = document.createElement('input');
        quantidade.type = 'number';
        quantidade.name = 'produtos[][quantidade]';
        quantidade.classList.add('form-control');
        quantidade.required = true;
        quantidade.min = 1;
        quantidade.value = 1;

        // Preço Unitário
        const preco = document.createElement('input');
        preco.type = 'number';
        preco.name = 'produtos[][preco_unitario]';
        preco.classList.add('form-control');
        preco.step = 0.01;
        preco.required = true;
        preco.value = 0;

        // Total
        const total = document.createElement('input');
        total.type = 'text';
        total.classList.add('form-control', 'total-item');
        total.readOnly = true;
        total.value = '0.00';

        // Botão remover
        const remover = document.createElement('button');
        remover.type = 'button';
        remover.classList.add('btn', 'btn-danger', 'btn-sm');
        remover.innerText = 'Remover';
        remover.onclick = () => {
            linha.remove();
            atualizarTotalGeral();
        };

        // Eventos de atualização
        select.onchange = () => {
            const precoSelecionado = select.selectedOptions[0].dataset.preco || 0;
            preco.value = parseFloat(precoSelecionado).toFixed(2);
            atualizarLinha();
        };

        quantidade.oninput = atualizarLinha;
        preco.oninput = atualizarLinha;

        function atualizarLinha() {
            const subtotal = parseFloat(quantidade.value || 0) * parseFloat(preco.value || 0);
            total.value = subtotal.toFixed(2);
            atualizarTotalGeral();
        }

        // Monta os tds
        const td1 = document.createElement('td'); td1.appendChild(select);
        const td2 = document.createElement('td'); td2.appendChild(quantidade);
        const td3 = document.createElement('td'); td3.appendChild(preco);
        const td4 = document.createElement('td'); td4.appendChild(total);
        const td5 = document.createElement('td'); td5.appendChild(remover);

        linha.append(td1, td2, td3, td4, td5);
        tabela.appendChild(linha);

        // Dispara onchange para preencher preço automaticamente
        setTimeout(() => {
            select.dispatchEvent(new Event('change'));
        }, 0);
    }

    function atualizarTotalGeral() {
        let total = 0;
        document.querySelectorAll('.total-item').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('total-geral').innerText = total.toFixed(2);
    }
</script>
@endsection
