<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Industria;
use App\Models\Pedido;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate para verificar se o usuário é admin (usando coluna is_admin)
        Gate::define('isAdmin', function ($user) {
            return $user->is_admin ?? false;
        });

        // Variáveis globais compartilhadas com todas as views
        View::composer('*', function ($view) {
            $industria = null;
            $todas = Industria::all();

            if (session()->has('industry_id')) {
                $industria = Industria::find(session('industry_id'));
            }

            $notificacoes = [];
            if (Auth::check()) {
                $pedidosAbertos = Pedido::where('status', 'Aberto')->count();
                if ($pedidosAbertos > 0) {
                    $notificacoes[] = "Você tem $pedidosAbertos pedido(s) em aberto.";
                }
                $notificacoes[] = "Seu perfil está 90% completo.";
            }

            $view->with([
                'currentIndustry' => $industria,
                'industries' => $todas,
                'notificacoes' => $notificacoes,
            ]);
        });
    }
}
