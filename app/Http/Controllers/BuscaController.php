<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Pedido;

class BuscaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');

        $clientes = Cliente::where('nome', 'like', "%{$q}%")->get();
        $produtos = Produto::where('nome', 'like', "%{$q}%")->get();
        $pedidos = Pedido::where('id', $q)->get();

        return view('busca.resultados', compact('q', 'clientes', 'produtos', 'pedidos'));
    }
}

