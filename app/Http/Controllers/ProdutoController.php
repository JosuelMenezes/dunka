<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Industria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::with('industria');

        // Busca
        if ($request->has('busca') && !empty($request->busca)) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('codigo', 'like', "%{$busca}%")
                  ->orWhere('descricao', 'like', "%{$busca}%")
                  ->orWhere('ean', 'like', "%{$busca}%")
                  ->orWhere('ncm', 'like', "%{$busca}%");
            });
        }

        // Filtro por indústria
        if ($request->has('industria_id') && !empty($request->industria_id)) {
            $query->where('industria_id', $request->industria_id);
        }

        // Paginação
        $produtos = $query->paginate(10);
        $industrias = Industria::all();

        return view('produtos.index', compact('produtos', 'industrias'));
    }

    public function create()
    {
        $industrias = Industria::all();
        return view('produtos.create', compact('industrias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'descricao' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // 2MB max
            'variacao_ipi' => 'nullable|numeric|min:0|max:100',
            'ean' => 'nullable|string|max:20',
            'ncm' => 'nullable|string|max:20',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
            'industria_id' => 'required|exists:industrias,id',
        ]);

        $dados = $request->all();

        // Processar o upload da foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoNome = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('produtos', $fotoNome, 'public');
            $dados['foto'] = $fotoNome;
        }

        Produto::create($dados);

        return redirect()->route('produtos.index')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        $industrias = Industria::all();
        return view('produtos.edit', compact('produto', 'industrias'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'codigo' => 'nullable|string|max:50',
            'descricao' => 'nullable|string',
            'foto' => 'nullable|image|max:2048', // 2MB max
            'variacao_ipi' => 'nullable|numeric|min:0|max:100',
            'ean' => 'nullable|string|max:20',
            'ncm' => 'nullable|string|max:20',
            'preco' => 'required|numeric',
            'estoque' => 'required|integer',
            'industria_id' => 'required|exists:industrias,id',
        ]);

        $dados = $request->all();

        // Processar o upload da foto
        if ($request->hasFile('foto')) {
            // Remover foto antiga se existir
            if ($produto->foto && Storage::disk('public')->exists('produtos/' . $produto->foto)) {
                Storage::disk('public')->delete('produtos/' . $produto->foto);
            }

            $foto = $request->file('foto');
            $fotoNome = time() . '.' . $foto->getClientOriginalExtension();
            $foto->storeAs('produtos', $fotoNome, 'public');
            $dados['foto'] = $fotoNome;
        }

        $produto->update($dados);

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->foto && Storage::disk('public')->exists('produtos/' . $produto->foto)) {
            Storage::disk('public')->delete('produtos/' . $produto->foto);
        }

        $produto->delete();

        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
    }

    public function downloadPdf(Request $request)
    {
        $query = Produto::with('industria');

        // Filtros para o PDF
        if ($request->has('industria_id') && !empty($request->industria_id)) {
            $query->where('industria_id', $request->industria_id);
        }

        if ($request->has('busca') && !empty($request->busca)) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('codigo', 'like', "%{$busca}%");
            });
        }

        $produtos = $query->get();

        $pdf = PDF::loadView('produtos.pdf', compact('produtos'));
        return $pdf->download('produtos_' . date('YmdHis') . '.pdf');
    }
}
