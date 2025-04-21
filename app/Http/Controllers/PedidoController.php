<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Cliente;
use App\Models\Vendedor;
use App\Models\Produto;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::with(['cliente', 'vendedor']);

        // Aplicar filtros de busca
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->whereHas('cliente', function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%");
            })->orWhereHas('vendedor', function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%");
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por data
        if ($request->filled('data')) {
            $query->whereDate('data', $request->data);
        }

        // Ordenação e paginação
        $pedidos = $query->latest()->paginate(10);

        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $vendedores = Vendedor::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();

        return view('pedidos.create', compact('clientes', 'vendedores', 'produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'vendedor_id' => 'required|exists:vendedors,id',
            'data' => 'required|date',
            'observacoes' => 'nullable|string',
            'desconto_percentual' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|string|in:Aberto,Fechado,Cancelado',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco_unitario' => 'required|numeric|min:0',
            'produtos.*.desconto_percentual' => 'nullable|numeric|min:0|max:100',
        ]);

        // Criar o pedido
        $pedido = Pedido::create([
            'cliente_id' => $request->cliente_id,
            'vendedor_id' => $request->vendedor_id,
            'data' => $request->data,
            'status' => $request->status,
            'observacoes' => $request->observacoes,
            'desconto_percentual' => $request->desconto_percentual ?? 0,
            'desconto_valor' => $request->desconto_valor ?? 0,
            'valor_total' => $request->valor_total ?? 0,
        ]);

        // Adicionar os itens ao pedido
        if (isset($request->produtos)) {
            foreach ($request->produtos as $item) {
                // Verificar se o produto existe
                if (!isset($item['produto_id']) || empty($item['produto_id'])) continue;

                // Calcular valores
                $quantidade = $item['quantidade'];
                $precoUnitario = $item['preco_unitario'];
                $descontoPct = isset($item['desconto_percentual']) ? floatval($item['desconto_percentual']) : 0;

                $subtotal = $quantidade * $precoUnitario;
                $descontoValor = $subtotal * ($descontoPct / 100);
                $totalItem = $subtotal - $descontoValor;

                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'desconto_percentual' => $descontoPct,
                    'total' => $totalItem,
                ]);
            }
        }

        // Calcular e atualizar totais
        $pedido->atualizarTotais();

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', 'Pedido cadastrado com sucesso!');
    }

    public function show($id)
    {
        $pedido = Pedido::with(['cliente', 'vendedor', 'itens.produto'])
            ->findOrFail($id);

        return view('pedidos.show', compact('pedido'));
    }

    public function edit($id)
    {
        $pedido = Pedido::with(['cliente', 'vendedor', 'itens.produto'])->findOrFail($id);

        // Verificar se o pedido já está fechado ou cancelado
        if ($pedido->status !== 'Aberto') {
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('error', 'Este pedido não pode ser editado pois está ' . strtolower($pedido->status) . '.');
        }

        $clientes = Cliente::orderBy('nome')->get();
        $vendedores = Vendedor::orderBy('nome')->get();
        $produtos = Produto::orderBy('nome')->get();

        return view('pedidos.edit', compact('pedido', 'clientes', 'vendedores', 'produtos'));
    }

    public function update(Request $request, $id)
    {
        $pedido = Pedido::findOrFail($id);

        // Verificar se o pedido já está fechado ou cancelado
        if ($pedido->status !== 'Aberto') {
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('error', 'Este pedido não pode ser editado pois está ' . strtolower($pedido->status) . '.');
        }

        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'vendedor_id' => 'required|exists:vendedors,id',
            'data' => 'required|date',
            'observacoes' => 'nullable|string',
            'desconto_percentual' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|string|in:Aberto,Fechado,Cancelado',
            'produtos.*.produto_id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco_unitario' => 'required|numeric|min:0',
            'produtos.*.desconto_percentual' => 'nullable|numeric|min:0|max:100',
        ]);

        // Atualizar o pedido
        $pedido->update([
            'cliente_id' => $request->cliente_id,
            'vendedor_id' => $request->vendedor_id,
            'data' => $request->data,
            'status' => $request->status,
            'observacoes' => $request->observacoes,
            'desconto_percentual' => $request->desconto_percentual ?? 0,
            'desconto_valor' => $request->desconto_valor ?? 0,
            'valor_total' => $request->valor_total ?? 0,
        ]);

        // Remover os itens antigos
        $pedido->itens()->delete();

        // Adicionar os novos itens
        if (isset($request->produtos)) {
            foreach ($request->produtos as $item) {
                // Verificar se o produto existe
                if (!isset($item['produto_id']) || empty($item['produto_id'])) continue;

                // Calcular valores
                $quantidade = $item['quantidade'];
                $precoUnitario = $item['preco_unitario'];
                $descontoPct = isset($item['desconto_percentual']) ? floatval($item['desconto_percentual']) : 0;

                $subtotal = $quantidade * $precoUnitario;
                $descontoValor = $subtotal * ($descontoPct / 100);
                $totalItem = $subtotal - $descontoValor;

                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $quantidade,
                    'preco_unitario' => $precoUnitario,
                    'desconto_percentual' => $descontoPct,
                    'total' => $totalItem,
                ]);
            }
        }

        // Calcular e atualizar totais
        $pedido->atualizarTotais();

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $pedido = Pedido::findOrFail($id);

        // Verificar se o pedido já está fechado
        if ($pedido->status === 'Fechado') {
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('error', 'Este pedido não pode ser excluído pois já está finalizado. Considere cancelá-lo.');
        }

        // Remover os itens
        $pedido->itens()->delete();

        // Remover o pedido
        $pedido->delete();

        return redirect()->route('pedidos.index')
            ->with('success', 'Pedido excluído com sucesso!');
    }

    public function downloadPdf($id)
    {
        $pedido = Pedido::with(['cliente', 'vendedor', 'itens.produto'])->findOrFail($id);
        $pdf = Pdf::loadView('pedidos.pdf', compact('pedido'));
        return $pdf->download('pedido_' . $pedido->id . '.pdf');
    }

    public function duplicar($id)
    {
        $pedidoOriginal = Pedido::with('itens')->findOrFail($id);

        $novoPedido = Pedido::create([
            'cliente_id'   => $pedidoOriginal->cliente_id,
            'vendedor_id'  => $pedidoOriginal->vendedor_id,
            'data'         => now()->format('Y-m-d'),
            'status'       => 'Aberto',
            'observacoes'  => $pedidoOriginal->observacoes,
            'desconto_percentual' => $pedidoOriginal->desconto_percentual,
            'desconto_valor' => 0, // Será calculado depois
            'valor_total' => 0, // Será calculado depois
        ]);

        foreach ($pedidoOriginal->itens as $item) {
            PedidoItem::create([
                'pedido_id'       => $novoPedido->id,
                'produto_id'      => $item->produto_id,
                'quantidade'      => $item->quantidade,
                'preco_unitario'  => $item->preco_unitario,
                'desconto_percentual' => $item->desconto_percentual ?? 0,
                'total'           => $item->total,
            ]);
        }

        // Atualizar os totais do novo pedido
        $novoPedido->atualizarTotais();

        return redirect()->route('pedidos.edit', $novoPedido->id)
            ->with('success', 'Pedido duplicado com sucesso! Você pode editar os detalhes deste novo pedido.');
    }

    public function finalizar($id)
    {
        $pedido = Pedido::findOrFail($id);

        // Verificar se o pedido já está finalizado ou cancelado
        if ($pedido->status !== 'Aberto') {
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('error', 'Este pedido não pode ser finalizado pois já está ' . strtolower($pedido->status) . '.');
        }

        // Finalizar o pedido
        $pedido->update(['status' => 'Fechado']);

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', 'Pedido finalizado com sucesso!');
    }

    public function cancelar($id)
    {
        $pedido = Pedido::findOrFail($id);

        // Verificar se o pedido já está finalizado ou cancelado
        if ($pedido->status !== 'Aberto') {
            return redirect()->route('pedidos.show', $pedido->id)
                ->with('error', 'Este pedido não pode ser cancelado pois já está ' . strtolower($pedido->status) . '.');
        }

        // Cancelar o pedido
        $pedido->update(['status' => 'Cancelado']);

        return redirect()->route('pedidos.show', $pedido->id)
            ->with('success', 'Pedido cancelado com sucesso!');
    }
}
