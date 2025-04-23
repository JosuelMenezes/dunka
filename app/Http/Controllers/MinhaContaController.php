<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuracao;

class MinhaContaController extends Controller
{
    public function index()
    {
        $config = Configuracao::first();
        return view('minha_conta.index', compact('config'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'logomarca' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $config = Configuracao::firstOrNew();

        if ($request->hasFile('logomarca')) {
            $logoPath = $request->file('logomarca')->store('logomarcas', 'public');
            $config->logomarca = $logoPath;
        }

        $config->fill($request->except('logomarca'));
        $config->save();

        return redirect()->route('minha-conta.index')->with('success', 'Configurações salvas com sucesso!');
    }
}
