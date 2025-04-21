<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    public function index()
    {
        $vendedores = Vendedor::all();
        return view('vendedores.index', compact('vendedores'));
    }

    public function create()
    {
        return view('vendedores.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'nullable|email',
        'telefone' => 'nullable|string|max:20',
        'comissao' => 'nullable|numeric',
    ]);

    $dados = $request->all();
    $dados['comissao'] = $dados['comissao'] ?? 0;

    Vendedor::create($dados);

    return redirect()->route('vendedores.index')->with('success', 'Vendedor cadastrado com sucesso!');
}

    public function edit($id)
    {
        $vendedor = Vendedor::findOrFail($id);
        return view('vendedores.edit', compact('vendedor'));
    }

    public function update(Request $request, $id)
    {
        $vendedor = Vendedor::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email',
            'telefone' => 'nullable|string|max:20',
            'comissao' => 'nullable|numeric',
        ]);

        $vendedor->update($request->all());

        return redirect()->route('vendedores.index')->with('success', 'Vendedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        Vendedor::findOrFail($id)->delete();

        return redirect()->route('vendedores.index')->with('success', 'Vendedor excluído com sucesso!');
    }
}
