<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 15px;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }
        td {
            padding: 6px 8px;
            font-size: 11px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #ccc;
        }
        .header-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Catálogo de Produtos - DUNKA Sistema Comercial</h1>

    <div class="header-info">
        <strong>Data de Geração:</strong> {{ date('d/m/Y H:i:s') }}<br>
        <strong>Total de Produtos:</strong> {{ $produtos->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>EAN</th>
                <th>NCM</th>
                <th>IPI %</th>
                <th>Indústria</th>
                <th>Preço</th>
                <th>Estoque</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produtos as $produto)
                <tr>
                    <td>{{ $produto->codigo ?: '-' }}</td>
                    <td>{{ $produto->nome }}</td>
                    <td>{{ $produto->ean ?: '-' }}</td>
                    <td>{{ $produto->ncm ?: '-' }}</td>
                    <td>{{ $produto->variacao_ipi ?: '0' }}%</td>
                    <td>{{ $produto->industria->nome ?? 'N/A' }}</td>
                    <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                    <td>{{ $produto->estoque }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        DUNKA Sistema Comercial - Relatório gerado em {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
