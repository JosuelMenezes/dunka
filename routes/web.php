<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MinhaContaController;
use App\Http\Controllers\{
    IndustriaController, ProdutoController, ClienteController, VendedorController,
    OrcamentoController, PedidoController, BuscaController, AgendaController, DashboardController

};

/* -------------------------- PÁGINA PÚBLICA -------------------------- */
Route::get('/', fn() => view('welcome'));

/* ------------------------- AUTH DEFAULT ----------------------------- */
Auth::routes();

// Bloqueia o acesso direto ao /register
Route::get('/register', fn() => abort(403, 'Cadastro não permitido.'));

/* -------------------------- ROTAS AUTENTICADAS ---------------------- */
Route::middleware('auth')->group(function () {

    /* DASHBOARD */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/dashboard/export/pdf', [DashboardController::class, 'exportPdf'])->name('dashboard.export.pdf');
    Route::get('/dashboard/export/excel', [DashboardController::class, 'exportExcel'])->name('dashboard.export.excel');

    /* USUÁRIOS – restrito ao administrador */
    Route::middleware('can:isAdmin')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    });

    /* INDÚSTRIAS */
    Route::resource('industrias', IndustriaController::class)->except(['show']);
    Route::post('/settings/industry/{id}', [IndustriaController::class, 'selecionarIndustria'])->name('industria.selecionar');

    /* PRODUTOS */
    Route::resource('produtos', ProdutoController::class)->except(['show']);
    Route::get('/produtos/pdf', [ProdutoController::class, 'downloadPdf'])->name('produtos.downloadPdf');

    /* CLIENTES E VENDEDORES */
    Route::resource('clientes', ClienteController::class)->except(['show']);
    Route::resource('vendedores', VendedorController::class)->except(['show']);

    /* ORÇAMENTOS */
    Route::resource('orcamentos', OrcamentoController::class)->except(['edit', 'update', 'destroy', 'show']);
    Route::get('/orcamentos/{id}/edit', [OrcamentoController::class,'edit'])->name('orcamentos.edit');
    Route::put('/orcamentos/{id}', [OrcamentoController::class,'update'])->name('orcamentos.update');
    Route::delete('/orcamentos/{id}', [OrcamentoController::class,'destroy'])->name('orcamentos.destroy');
    Route::get('/orcamentos/{id}/pdf', [OrcamentoController::class,'downloadPdf'])->name('orcamentos.pdf');
    Route::get('/orcamentos/{id}', [OrcamentoController::class,'show'])->name('orcamentos.show');

    /* PEDIDOS */
    Route::resource('pedidos', PedidoController::class);
    Route::get('/pedidos/{id}/pdf', [PedidoController::class,'downloadPdf'])->name('pedidos.pdf');
    Route::post('/pedidos/{id}/duplicar', [PedidoController::class,'duplicar'])->name('pedidos.duplicar');
    Route::post('/pedidos/{id}/finalizar', [PedidoController::class,'finalizar'])->name('pedidos.finalizar');
    Route::post('/pedidos/{id}/cancelar', [PedidoController::class,'cancelar'])->name('pedidos.cancelar');

    /* AGENDA / PERFIL / BUSCA */
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');

    Route::get('/busca', [BuscaController::class, 'index'])->name('busca.index');

    /* IMPORTAÇÃO DE PRODUTOS / IMAGENS – vinculado à indústria */
  Route::prefix('/minha-conta/industrias/{id}')->group(function () {
        Route::get('/produtos/importar', [IndustriaController::class, 'importarProdutos'])->name('industrias.produtos.importar');
        Route::get('/produtos/modelo', [IndustriaController::class, 'gerarModeloPlanilha'])->name('industrias.produtos.modelo');
        Route::post('/produtos/processar', [IndustriaController::class, 'processarImportacao'])->name('industrias.produtos.processar');

        Route::get('/produtos/imagens', [IndustriaController::class, 'importarImagens'])->name('industrias.produtos.imagens');
        Route::get('/produtos/sem-imagem', [IndustriaController::class, 'listaProdutosSemImagem'])->name('industrias.produtos.sem-imagem');
        Route::post('/produtos/processar-imagens', [IndustriaController::class, 'processarImagens'])->name('industrias.produtos.processar-imagens');
    });
});

/* ------------------------- /home ANTIGO ----------------------------- */
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/minha-conta', [MinhaContaController::class, 'index'])->name('minha-conta.index');
Route::post('/minha-conta', [MinhaContaController::class, 'update'])->name('minha-conta.update');

Route::post('pedidos/{pedido}/duplicar', [PedidoController::class, 'duplicar'])
     ->name('pedidos.duplicar');

// Rota para PDFs de catálogos
Route::get('catalogos/{file}', function ($file) {
    $path = storage_path("app/public/catalogos/{$file}");

    if (!file_exists($path)) abort(404);
    return response()->file($path);
})->where('file', '.*')->name('catalogos.pdf');

// Rota para imagens de logomarcas
Route::get('logomarcas/{file}', function ($file) {
    $path = storage_path("app/public/logomarcas/{$file}");

    if (!file_exists($path)) abort(404);
    return response()->file($path);
})->where('file', '.*')->name('logomarcas.img');
