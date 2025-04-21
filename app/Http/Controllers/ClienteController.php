<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
{
    $clientes = Cliente::all();

    // Dados para o gráfico
    $totalAtivos = 80; // Placeholder - deve ser calculado com base em lógica real
    $totalInativos = 15; // Placeholder
    $novosClientes = 5; // Clientes registrados nos últimos 30 dias

    return view('clientes.index', compact('clientes', 'totalAtivos', 'totalInativos', 'novosClientes'));
}

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
{
    $regras = [
        'nome' => 'required|string|max:255',
        'tipo_pessoa' => 'required|in:F,J',
        'documento' => 'nullable|string|max:20',
        'razao_social' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'email_secundario' => 'nullable|email|max:255',
        'telefone' => 'nullable|string|max:20',
        'inscricao_estadual' => 'nullable|string|max:20',
        'suframa' => 'nullable|string|max:20',
        'endereco' => 'nullable|string',
        'informacoes_adicionais' => 'nullable|string',
    ];

    // Regras adicionais para pessoa jurídica
    if ($request->tipo_pessoa === 'J') {
        $regras['razao_social'] = 'required|string|max:255';
    }

    $request->validate($regras);

    // Preparar os dados para criação
    $dados = $request->all();

    // Tratar o campo ativo - será 1 se o checkbox estiver marcado, 0 caso contrário
    $dados['ativo'] = $request->has('ativo') ? 1 : 0;

    // Criar com os dados processados
    Cliente::create($dados);

    return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
}




public function edit($id)
{
    $cliente = Cliente::findOrFail($id);

    // Dados para o resumo (últimos 6 meses)
    $seisMesesAtras = now()->subMonths(6);

    // Valida se a tabela de pedidos existe e tem os campos necessários
    try {
        // Consulta de pedidos com namespace correto
        $pedidosRecentes = \App\Models\Pedido::where('cliente_id', $id)
                                ->where('data', '>=', $seisMesesAtras)
                                ->orderBy('data', 'desc')
                                ->take(5)
                                ->get();

        $totalPedidos = \App\Models\Pedido::where('cliente_id', $id)
                             ->where('data', '>=', $seisMesesAtras)
                             ->count();

        $valorTotal = \App\Models\Pedido::where('cliente_id', $id)
                            ->where('data', '>=', $seisMesesAtras)
                            ->sum('total');

        $ultimoPedido = \App\Models\Pedido::where('cliente_id', $id)
                               ->latest('data')
                               ->first();

        $ultimoContato = $ultimoPedido ? \Carbon\Carbon::parse($ultimoPedido->data)->format('d/m/Y') : '-';
    } catch (\Exception $e) {
        // Se houver erros (tabela não existe, campos faltando, etc), use valores padrão
        $pedidosRecentes = collect([]);
        $totalPedidos = 0;
        $valorTotal = 0;
        $ultimoContato = '-';
    }

    return view('clientes.edit', compact(
        'cliente',
        'pedidosRecentes',
        'totalPedidos',
        'valorTotal',
        'ultimoContato'
    ));
}
public function update(Request $request, $id)
{
    $cliente = Cliente::findOrFail($id);
    $regras = [
        'nome' => 'required|string|max:255',
        'tipo_pessoa' => 'required|in:F,J',
        'documento' => 'nullable|string|max:20',
        'razao_social' => 'nullable|string|max:255',
        'email' => 'nullable|email|max:255',
        'email_secundario' => 'nullable|email|max:255',
        'telefone' => 'nullable|string|max:20',
        'inscricao_estadual' => 'nullable|string|max:20',
        'suframa' => 'nullable|string|max:20',
        'endereco' => 'nullable|string',
        'informacoes_adicionais' => 'nullable|string',
    ];

    // Regras adicionais para pessoa jurídica
    if ($request->tipo_pessoa === 'J') {
        $regras['razao_social'] = 'required|string|max:255';
    }

    $request->validate($regras);

    // Preparar os dados para atualização
    $dados = $request->all();

    // Tratar o campo ativo - será 1 se o checkbox estiver marcado, 0 caso contrário
    $dados['ativo'] = $request->has('ativo') ? 1 : 0;

    // Atualizar com os dados processados
    $cliente->update($dados);

    return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
}

    public function destroy($id)
    {
        Cliente::findOrFail($id)->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
    }
}
