<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Vendedor;
use App\Models\Industria;
use App\Models\Meta;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardExport;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /* ===========================  VIEW PRINCIPAL  =========================== */
    public function index()
    {
        $industrias = Industria::orderBy('nome')->get();
        $produtos   = Produto::orderBy('nome')->get();

        return view('dashboard.index', compact('industrias', 'produtos'));
    }

    /* ============================  END‑POINT AJAX  =========================== */
    public function data(Request $request): JsonResponse
    {
        $data = $this->getDashboardData($request);

        return response()->json([
            'html'        => view('dashboard._partials', $data)->render(),
            'labelsMes'   => $data['vendasPorMes']->pluck('mes'),
            'valoresMes'  => $data['vendasPorMes']->pluck('valor'),
            'labelsVend'  => $data['vendasPorVendedor']->pluck('nome'),
            'valoresVend' => $data['vendasPorVendedor']->pluck('valor'),
        ]);
    }

    /* ========================  EXPORTAÇÕES PDF / EXCEL  ====================== */
    public function exportPdf(Request $request)
    {
        $data = $this->getDashboardData($request);

        $pdf = Pdf::loadView('dashboard.export_pdf', $data);
        return $pdf->download('dashboard_resumo.pdf');
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getDashboardData($request);

        return Excel::download(new DashboardExport($data), 'dashboard_resumo.xlsx');
    }

    /* =====================  MÉTODO CENTRAL DE AGREGAÇÃO  ===================== */
    private function getDashboardData(Request $request): array
    {
        /* --------- Período e filtros --------- */
        [$inicio, $fim] = explode(
            '|',
            $request->input('periodo')
                ?? now()->startOfMonth()->format('Y-m-d') . '|' . now()->format('Y-m-d')
        );

        $industriaId = $request->input('industria_id');
        $produtoId   = $request->input('produto_id');

        /* --------- Coleção base de pedidos --------- */
        $pedidos = Pedido::query()
            ->whereBetween('created_at', [$inicio, $fim]);

        if ($produtoId || $industriaId) {
            $pedidos->whereHas('itens.produto', function ($q) use ($produtoId, $industriaId) {
                if ($produtoId)   $q->where('produtos.id', $produtoId);
                if ($industriaId) $q->where('produtos.industria_id', $industriaId);
            });
        }

        $idsPedidos = $pedidos->pluck('id');

        /* --------- Indicadores --------- */
        $resumoMes = Pedido::selectRaw('
                COUNT(*)                            as total_pedidos,
                SUM(valor_total + desconto_valor)   as bruto,
                SUM(desconto_valor)                 as desconto,
                SUM(valor_total)                    as liquido
            ')
            ->whereIn('id', $idsPedidos)
            ->first();

        $vendasPorMes = Pedido::selectRaw("
                DATE_FORMAT(created_at,'%b/%y') as mes,
                SUM(valor_total) as valor
            ")
            ->whereIn('id', $idsPedidos)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $topProdutos = Produto::selectRaw('produtos.nome, SUM(pedido_items.quantidade) as qtd')
            ->join('pedido_items', 'produtos.id', '=', 'pedido_items.produto_id')
            ->whereIn('pedido_items.pedido_id', $idsPedidos)
            ->groupBy('produtos.id', 'produtos.nome')
            ->orderByDesc('qtd')
            ->limit(5)
            ->get();

        $vendasPorVendedor = Vendedor::selectRaw('vendedors.nome, COALESCE(SUM(pedidos.valor_total),0) as valor')
            ->leftJoin('pedidos', 'vendedors.id', '=', 'pedidos.vendedor_id')
            ->whereIn('pedidos.id', $idsPedidos)
            ->groupBy('vendedors.id', 'vendedors.nome')
            ->orderByDesc('valor')
            ->get();

        /* --------- Meta Geral --------- */
        $metaGeral = Meta::whereNull('vendedor_id')
            ->whereDate('inicio', '<=', $inicio)
            ->whereDate('fim',    '>=', $fim)
            ->first(); // pode ser null

        /* --------- Ranking Clientes --------- */
        $rankingClientes = Cliente::selectRaw('clientes.nome, SUM(pedidos.valor_total) as total')
            ->join('pedidos', 'clientes.id', '=', 'pedidos.cliente_id')
            ->whereIn('pedidos.id', $idsPedidos)
            ->groupBy('clientes.id', 'clientes.nome')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return compact(
            'resumoMes',
            'vendasPorMes',
            'topProdutos',
            'vendasPorVendedor',
            'metaGeral',
            'rankingClientes'
        );
    }
}
