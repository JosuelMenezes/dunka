<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $pedido->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 22px;
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        .info-block {
            margin-bottom: 20px;
        }
        .info-block h2 {
            font-size: 14px;
            margin: 0 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: white;
        }
        .status-aberto {
            background-color: #0d6efd;
        }
        .status-fechado {
            background-color: #198754;
        }
        .status-cancelado {
            background-color: #dc3545;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-table {
            width: 350px;
            margin-left: auto;
            margin-right: 0;
            margin-top: 10px;
        }
        .totals-table td {
            padding: 5px 8px;
        }
        .totals-table .highlight {
            font-weight: bold;
            font-size: 14px;
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .red {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEDIDO DE VENDA #{{ $pedido->id }}</h1>
        <p>
            DUNKA Sistema Comercial &bull;
            Data: {{ \Carbon\Carbon::parse($pedido->data)->format('d/m/Y') }} &bull;
            <span class="status-badge status-{{ strtolower($pedido->status) }}">{{ $pedido->status }}</span>
        </p>
    </div>

    <div class="info-grid">
        <div class="info-block">
            <h2>CLIENTE</h2>
            <p>
                <strong>{{ $pedido->cliente->nome }}</strong><br>
                @if($pedido->cliente->cpf_cnpj)
                    CPF/CNPJ: {{ $pedido->cliente->cpf_cnpj }}<br>
                @endif
                @if($pedido->cliente->telefone)
                    Telefone: {{ $pedido->cliente->telefone }}<br>
                @endif
                @if($pedido->cliente->email)
                    E-mail: {{ $pedido->cliente->email }}<br>
                @endif
                @if($pedido->cliente->endereco)
                    Endereço: {{ $pedido->cliente->endereco }}
                @endif
            </p>
        </div>

        <div class="info-block">
            <h2>VENDEDOR</h2>
            <p>
                <strong>{{ $pedido->vendedor->nome }}</strong><br>
                @if($pedido->vendedor->email)
                    E-mail: {{ $pedido->vendedor->email }}<br>
                @endif
                @if($pedido->vendedor->telefone)
                    Telefone: {{ $pedido->vendedor->telefone }}
                @endif
            </p>
        </div>
    </div>

    @if($pedido->observacoes)
    <div class="info-block">
        <h2>OBSERVAÇÕES</h2>
        <p>{{ $pedido->observacoes }}</p>
    </div>
    @endif

    <div class="info-block">
        <h2>PRODUTOS</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">ITEM</th>
                    <th>PRODUTO</th>
                    <th style="width: 80px;">QTD</th>
                    <th style="width: 100px;">PREÇO UNIT.</th>
                    <th style="width: 120px;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedido->itens as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div>{{ $item->produto->nome ?? 'Produto removido' }}</div>
                        @if($item->produto && $item->produto->codigo)
                            <small>Cód: {{ $item->produto->codigo }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantidade }}</td>
                    <td class="text-right">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr>
                <td width="60%"><strong>Subtotal:</strong></td>
                <td class="text-right">R$ {{ number_format($pedido->getSubtotalAttribute(), 2, ',', '.') }}</td>
            </tr>
            @if($pedido->desconto_valor > 0)
            <tr>
                <td><strong>Desconto ({{ number_format($pedido->desconto_percentual, 2, ',', '.') }}%):</strong></td>
                <td class="text-right red">-R$ {{ number_format($pedido->desconto_valor, 2, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="highlight">
                <td><strong>TOTAL:</strong></td>
                <td class="text-right">R$ {{ number_format($pedido->total, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Documento gerado por DUNKA Sistema Comercial em {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
