<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Industria;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function empresa()
    {
        $industria = Industria::first();
        return view('minha-conta.empresa', compact('industria'));
    }

    public function salvarEmpresa(Request $request)
    {
        $request->validate([
            'razao_social' => 'required|string|max:255',
            'fantasia'     => 'nullable|string|max:255',
            'cnpj'         => 'nullable|string|max:20',
            'ie'           => 'nullable|string|max:20',
            'telefone'     => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:255',
            'endereco'     => 'nullable|string|max:255',
            'comissao'     => 'nullable|numeric|min:0',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $industria = Industria::first();

        $industria->fill($request->only([
            'razao_social', 'fantasia', 'cnpj', 'ie', 'telefone',
            'email', 'endereco', 'comissao'
        ]));

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('public/logos');
            $industria->logo_path = Storage::url($logoPath);
        }

        $industria->save();

        return redirect()->route('minha-conta.empresa')->with('success', 'Dados atualizados com sucesso.');
    }
    public function index()
{
    return view('minha-conta.index');
}

}
