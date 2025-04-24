<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Vendedor;
use App\Models\Orcamento;
use App\Models\OrcamentoItem;
use Illuminate\Http\Request;
use App\Models\Configuracao;
use Barryvdh\DomPDF\Facade\Pdf;


class OrcamentoController extends Controller
{
    public function index()
    {
        $orcamentos = Orcamento::with(['cliente', 'vendedor', 'itens'])->latest()->get();
        return view('orcamentos.index', compact('orcamentos'));
    }


    public function create()
    {
        $clientes = Cliente::all();
        $vendedores = Vendedor::all();
        $produtos = Produto::all();
        return view('orcamentos.create', compact('clientes', 'vendedores', 'produtos'));
    }

    public function show($id)
{
    $orcamento = Orcamento::with(['cliente', 'vendedor', 'itens.produto'])->findOrFail($id);
    return view('orcamentos.show', compact('orcamento'));
}


public function store(Request $request)
{
    // Validação dos campos principais
    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'vendedor_id' => 'required|exists:vendedors,id',
        'data' => 'required|date',
        'validade_proposta' => 'nullable|string',
        'prazo_entrega' => 'nullable|string',
        'condicoes_pagamento' => 'nullable|string',
        'frete' => 'nullable|string',
    ]);

    // Criar o orçamento principal
    $orcamento = new Orcamento();
    $orcamento->cliente_id = $request->cliente_id;
    $orcamento->vendedor_id = $request->vendedor_id;
    $orcamento->data = $request->data;
    $orcamento->observacoes = $request->observacoes;
    $orcamento->validade_proposta = $request->validade_proposta;
    $orcamento->prazo_entrega = $request->prazo_entrega;
    $orcamento->condicoes_pagamento = $request->condicoes_pagamento;
    $orcamento->frete = $request->frete;
    $orcamento->save();

    // Verificar se temos produtos e se estão no formato correto
    if (isset($request->produtos) && is_array($request->produtos)) {
        foreach ($request->produtos as $item) {
            // Verificar se todas as propriedades necessárias existem
            if (isset($item['produto_id']) && isset($item['quantidade']) && isset($item['preco_unitario'])) {
                OrcamentoItem::create([
                    'orcamento_id' => $orcamento->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco_unitario'],
                    'total' => $item['quantidade'] * $item['preco_unitario'],
                ]);
            }
        }
    }

    return redirect()->route('orcamentos.index')->with('success', 'Orçamento cadastrado com sucesso!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'vendedor_id' => 'required|exists:vendedors,id',
        'data' => 'required|date',
        'observacoes' => 'nullable|string',
        'validade_proposta' => 'nullable|string',
        'prazo_entrega' => 'nullable|string',
        'condicoes_pagamento' => 'nullable|string',
        'frete' => 'nullable|string',
        'produtos.*.produto_id' => 'required|exists:produtos,id',
        'produtos.*.quantidade' => 'required|integer|min:1',
        'produtos.*.preco_unitario' => 'required|numeric|min:0',
    ]);

    $orcamento = Orcamento::findOrFail($id);
    $orcamento->update([
        'cliente_id' => $request->cliente_id,
        'vendedor_id' => $request->vendedor_id,
        'data' => $request->data,
        'observacoes' => $request->observacoes,
        'validade_proposta' => $request->validade_proposta,
        'prazo_entrega' => $request->prazo_entrega,
        'condicoes_pagamento' => $request->condicoes_pagamento,
        'frete' => $request->frete,
    ]);

    // Apaga os itens antigos e adiciona os novos
    $orcamento->itens()->delete();

    foreach ($request->produtos as $item) {
        OrcamentoItem::create([
            'orcamento_id' => $orcamento->id,
            'produto_id' => $item['produto_id'],
            'quantidade' => $item['quantidade'],
            'preco_unitario' => $item['preco_unitario'],
            'total' => $item['quantidade'] * $item['preco_unitario'],
        ]);
    }

    return redirect()->route('orcamentos.index')->with('success', 'Orçamento atualizado com sucesso!');
}

public function edit($id)
{
    $orcamento = Orcamento::with('itens.produto')->findOrFail($id);
    $clientes = Cliente::all();
    $vendedores = Vendedor::all();
    $produtos = Produto::all();

    return view('orcamentos.edit', compact('orcamento', 'clientes', 'vendedores', 'produtos'));
}

public function destroy($id)
{
    $orcamento = Orcamento::findOrFail($id);
    $orcamento->itens()->delete();
    $orcamento->delete();

    return redirect()->route('orcamentos.index')->with('success', 'Orçamento excluído com sucesso!');
}


public function downloadPdf($id)
{
    $orcamento = Orcamento::with(['cliente', 'vendedor', 'itens.produto'])->findOrFail($id);

    // Tentar buscar a configuração da empresa, mas não falhar se não existir
    try {
        $empresa = \App\Models\Configuracao::first();
    } catch (\Exception $e) {
        // Criar um objeto temporário caso o modelo não exista ou a tabela não exista
        $empresa = (object)[
            'nome_fantasia' => 'DUNKA Sistemas Comerciais',
            'cnpj' => '',
            'ie' => '',
            'telefone' => '',
            'email' => '',
            'endereco' => '',
            'logomarca' => null
        ];
    }

    $pdf = Pdf::loadView('orcamentos.pdf', compact('orcamento', 'empresa'));

    return $pdf->download('orcamento_' . $orcamento->id . '.pdf');
}
}


