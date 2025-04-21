<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Orçamento #{{ $orcamento->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Orçamento #{{ $orcamento->id }}</h2>

    <table>
        <tr>
            <td><strong>Cliente:</strong> {{ $orcamento->cliente->nome }}</td>
            <td><strong>Vendedor:</strong> {{ $orcamento->vendedor->nome }}</td>
            <td><strong>Data:</strong> {{ \Carbon\Carbon::parse($orcamento->data)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <h4>Itens:</h4>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Preço Unit.</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeral = 0; @endphp
            @foreach ($orcamento->itens as $item)
                @php $sub = $item->quantidade * $item->preco_unitario; $totalGeral += $sub; @endphp
                <tr>
                    <td>{{ $item->produto->nome }}</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($sub, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td><strong>R$ {{ number_format($totalGeral, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($orcamento->observacoes)
        <p><strong>Observações:</strong><br>{{ $orcamento->observacoes }}</p>
    @endif
</body>
</html>
