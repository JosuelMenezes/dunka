<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Orçamento #{{ $orcamento->id }}</title>
    <style>
        /* Estilos gerais */
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt; /* Reduzido de 11pt */
            line-height: 1.3; /* Reduzido de 1.4 */
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Cabeçalho */
        .header {
            padding: 10px 0; /* Reduzido de 20px */
            border-bottom: 1px solid #ccc;
            margin-bottom: 10px; /* Reduzido de 20px */
            display: flex;
            justify-content: space-between;
        }

        .header .logo {
            width: 140px; /* Reduzido de 180px */
            height: auto;
        }

        .header .company-info {
            text-align: right;
            font-size: 8pt; /* Reduzido de 10pt */
        }

        /* Título do orçamento */
        .title {
            text-align: center;
            margin: 10px 0; /* Reduzido de 20px */
        }

        .title h1 {
            font-size: 14pt; /* Reduzido de 18pt */
            margin: 0;
        }

        .title p {
            font-size: 8pt; /* Reduzido de 10pt */
            margin: 3px 0 0; /* Reduzido de 5px */
        }

        /* Blocos de informação */
        .info-block {
            margin-bottom: 10px; /* Reduzido de 20px */
        }

        .info-block h2 {
            font-size: 10pt; /* Reduzido de 12pt */
            padding-bottom: 3px; /* Reduzido de 5px */
            border-bottom: 1px solid #ccc;
            margin-bottom: 5px; /* Reduzido de 10px */
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 2px 3px; /* Reduzido de 3px 5px */
        }

        .info-label {
            font-weight: bold;
            width: 100px; /* Reduzido de 120px */
        }

        /* Tabela de produtos */
        table.products {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px; /* Reduzido de 30px */
        }

        table.products th,
        table.products td {
            border: 1px solid #ccc;
            padding: 5px; /* Reduzido de 8px */
            text-align: left;
            font-size: 8pt; /* Reduzido de 10pt */
        }

        table.products th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        table.products .text-right {
            text-align: right;
        }

        table.products .text-center {
            text-align: center;
        }

        table.products tfoot td {
            font-weight: bold;
        }

        /* Assinatura */
        .signature {
            margin-top: 25px; /* Reduzido de 50px */
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 200px; /* Reduzido de 250px */
            margin: 5px auto; /* Reduzido de 10px */
        }

        /* Condições */
        .conditions {
            margin-top: 20px; /* Reduzido de 40px */
            border-top: 1px solid #ccc;
            padding-top: 10px; /* Reduzido de 20px */
        }

        .conditions h2 {
            font-size: 10pt; /* Reduzido de 12pt */
            margin-bottom: 5px; /* Reduzido de 10px */
        }

        .conditions p {
            margin: 3px 0; /* Reduzido de 5px */
        }

        /* Rodapé */
        .footer {
            margin-top: 20px; /* Reduzido de 40px */
            border-top: 1px solid #ccc;
            padding-top: 5px; /* Reduzido de 10px */
            font-size: 7pt; /* Reduzido de 9pt */
            text-align: center;
            color: #777;
        }

        /*NOVA CSS*/

        .page-footer {
    position: fixed;
    bottom: 50px;
    left: 0;
    right: 0;
    padding: 10px 40px;
    font-size: 8pt;
}

.signature {
    text-align: center;
    margin-top: 20px;
}

.signature-line {
    border-top: 1px solid #000;
    width: 200px;
    margin: 5px auto;
}

.conditions-fixed {
    position: fixed;
    bottom: 130px;
    left: 40px;
    right: 40px;
    font-size: 8pt;
}

.conditions-fixed h2 {
    font-size: 9pt;
    margin-bottom: 5px;
    border-bottom: 1px solid #ccc;
}

.conditions-fixed p {
    margin: 2px 0;
}
.signature-fixed {
    position: fixed;
    bottom: 190px; /* espaço acima das condições */
    left: 0;
    right: 0;
    text-align: center;
    font-size: 8pt;
}

.signature-line {
    border-top: 1px solid #000;
    width: 200px;
    margin: 5px auto;
}

.conditions-fixed {
    position: fixed;
    bottom: 80px;
    left: 40px;
    right: 40px;
    font-size: 8pt;
}

.conditions-fixed h2 {
    font-size: 9pt;
    margin-bottom: 5px;
    border-bottom: 1px solid #ccc;
}

.conditions-fixed p {
    margin: 2px 0;
}

.page-footer {
    position: fixed;
    bottom: 10px;
    left: 0;
    right: 0;
    padding: 10px 40px;
    font-size: 7pt;
    text-align: center;
    color: #777;
}


    </style>
</head>
<body>
    <!-- Cabeçalho com dados da empresa -->
    <div class="header">
        <div>
            <!-- Logomarca da empresa -->
            @if(isset($empresa) && $empresa->logomarca)
                <img src="{{ public_path('storage/' . $empresa->logomarca) }}" class="logo" alt="Logo da Empresa">
            @else
                <img src="{{ public_path('img/logo3-dunka.png') }}" class="logo" alt="Logo DUNKA">
            @endif
        </div>

        <div class="company-info">
            <strong>{{ $empresa->nome_fantasia ?? 'DUNKA Sistemas Comerciais' }}</strong><br>
            @if($empresa->cnpj ?? '')
                CNPJ: {{ $empresa->cnpj }}<br>
            @endif
            @if($empresa->ie ?? '')
                IE: {{ $empresa->ie }}<br>
            @endif
            {{ $empresa->endereco ?? '' }}<br>
            {{ $empresa->telefone ?? '' }}<br>
            {{ $empresa->email ?? '' }}
        </div>
    </div>

    <!-- Título do orçamento -->
    <div class="title">
        <h1>ORÇAMENTO Nº {{ $orcamento->id }}</h1>
        <p>Data de emissão: {{ \Carbon\Carbon::parse($orcamento->data)->format('d/m/Y') }}</p>
    </div>

    <!-- Informações do cliente -->
    <div class="info-block">
        <h2>DADOS DO CLIENTE</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell info-label">Cliente:</div>
                <div class="info-cell"><strong>{{ $orcamento->cliente->nome }}</strong></div>
            </div>

            @if($orcamento->cliente->cpf_cnpj ?? '')
            <div class="info-row">
                <div class="info-cell info-label">CPF/CNPJ:</div>
                <div class="info-cell">{{ $orcamento->cliente->cpf_cnpj }}</div>
            </div>
            @endif

            @if($orcamento->cliente->telefone ?? '')
            <div class="info-row">
                <div class="info-cell info-label">Telefone:</div>
                <div class="info-cell">{{ $orcamento->cliente->telefone }}</div>
            </div>
            @endif

            @if($orcamento->cliente->email ?? '')
            <div class="info-row">
                <div class="info-cell info-label">E-mail:</div>
                <div class="info-cell">{{ $orcamento->cliente->email }}</div>
            </div>
            @endif

            @if($orcamento->cliente->endereco ?? '')
            <div class="info-row">
                <div class="info-cell info-label">Endereço:</div>
                <div class="info-cell">{{ $orcamento->cliente->endereco }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabela de produtos -->
    <div class="info-block">
        <h2>MATERIAIS</h2>
        <table class="products">
            <thead>
                <tr>
                    <th style="width: 50px;">Código</th>
                    <th>Nome</th>
                    <th style="width: 60px;">Marca</th>
                    <th style="width: 40px;" class="text-center">Qtde.</th>
                    <th style="width: 70px;" class="text-right">Vr. Unit. (R$)</th>
                    <th style="width: 70px;" class="text-right">Vr. Total (R$)</th>
                </tr>
            </thead>
            <tbody>
                @php $totalQtde = 0; $totalGeral = 0; @endphp

                @foreach($orcamento->itens as $item)
                    @php
                        $subtotal = $item->quantidade * $item->preco_unitario;
                        $totalQtde += $item->quantidade;
                        $totalGeral += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $item->produto->codigo ?? '-' }}</td>
                        <td>{{ $item->produto->nome }}</td>
                        <td>{{ $item->produto->marca ?? '-' }}</td>
                        <td class="text-center">{{ $item->quantidade }}</td>
                        <td class="text-right">{{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($subtotal, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td class="text-center"><strong>{{ $totalQtde }}</strong></td>
                    <td class="text-right"><strong>Total R$:</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalGeral, 2, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>



       <!-- Assinatura do Vendedor (fixa) -->
       <div class="signature-fixed">
        <div class="signature-line"></div>
        <p>Vendedor (Assinatura)</p>
    </div>

    <!-- Condições fixas -->
    <div class="conditions-fixed">
        <h2>OBSERVAÇÕES E CONDIÇÕES COMERCIAIS</h2>

        <p><strong>Observações:</strong> {{ $orcamento->observacoes ?? 'Nenhuma observação registrada.' }}</p>

        <p><strong>Validade da proposta:</strong> {{ $orcamento->validade_proposta ?? '15 dias a partir da data de emissão.' }}</p>

        <p><strong>Prazo de entrega:</strong> {{ $orcamento->prazo_entrega ?? 'Conforme disponibilidade.' }}</p>

        <p><strong>Condições de pagamento:</strong> {{ $orcamento->condicoes_pagamento ?? 'A combinar.' }}</p>

        <p><strong>Frete:</strong> {{ $orcamento->frete ?? 'Por conta do cliente, salvo condição previamente acordada.' }}</p>
    </div>

    <!-- Rodapé fixo -->
    <div class="page-footer">
        <p>Este orçamento foi gerado pelo sistema DUNKA - {{ date('d/m/Y H:i:s') }}</p>
        <p>Vendedor: {{ $orcamento->vendedor->nome }}{{ $orcamento->vendedor->telefone ? ' | Tel: '.$orcamento->vendedor->telefone : '' }}</p>
    </div>


</body>
</html>
