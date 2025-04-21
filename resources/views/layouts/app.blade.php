<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>DUNKA - Sistema Comercial</title>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'DUNKA Sistema Comercial') }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            width: 220px;
            background-color: #343a40;
            top: 0;
            left: 0;
        }
        .logo-sidebar-floating {
            height: 100px;
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
        }
        .logo-sidebar {
            height: 160px;
            width: auto;
        }
        .sidebar a {
            color: #fff;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
        }
        .content {
            margin-left: 220px;
            padding: 90px 20px 20px;
        }
        .topbar {
            position: fixed;
            left: 220px;
            top: 0;
            right: 0;
            height: 60px;
            background-color: #343a40;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }
        .topbar select {
            margin-right: 10px;
            max-width: 220px;
        }
        .whatsapp-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background-color: #25D366;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }
    </style>
</head>
<body>
    @guest
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'DUNKA Sistema Comercial') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                        </ul>
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Cadastrar') }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>
            <main class="py-4">
                @yield('content')
            </main>
        </div>
    @else
        {{-- Menu lateral --}}
        <div class="sidebar">
            <div class="position-relative text-center border-bottom" style="height: 80px;">
                <img src="{{ asset('img/logo3-dunka.png') }}"
                     alt="Logo DUNKA"
                     class="logo-sidebar-floating">
            </div>

            <a href="{{ route('dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            <a href="{{ route('pedidos.index') }}" class="{{ request()->is('pedidos*') ? 'active' : '' }}">🛒 Pedidos</a>
            <a href="{{ route('clientes.index') }}" class="{{ request()->is('clientes*') ? 'active' : '' }}">👥 Clientes</a>
            <a href="{{ route('produtos.index') }}" class="{{ request()->is('produtos*') ? 'active' : '' }}">📦 Produtos</a>
            <a href="{{ route('orcamentos.index') }}" class="{{ request()->is('orcamentos*') ? 'active' : '' }}">📝 Orçamentos</a>


            <a href="{{ route('usuarios.index') }}" class="{{ request()->is('usuarios*') ? 'active' : '' }}">👤 Usuários</a>


            <a href="{{ route('agenda.index') }}" class="{{ request()->is('agenda*') ? 'active' : '' }}">📅 Agenda</a>
            <a href="{{ route('profile') }}" class="{{ request()->is('profile') ? 'active' : '' }}">⚙️ Minha Conta</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-link w-100 text-start {{ request()->is('logout') ? 'active' : '' }}">
                    🚪 Sair
                </button>
            </form>

        </div>

        {{-- Conteúdo principal --}}
        <div class="content">
            <div class="topbar mb-3">
                {{-- Seletor de indústria à esquerda --}}
                <div class="d-flex align-items-center">
                    <select name="industry" class="form-select form-select-sm me-2" style="width: 200px;">
                        @foreach($industries ?? [$currentIndustry] as $ind)
                            <option value="{{ $ind->id ?? '' }}" {{ session('industry_id') == ($ind->id ?? '') ? 'selected' : '' }}>
                                {{ $ind->fantasy_name ?? ($ind->nome ?? 'Selecione') }}
                            </option>
                        @endforeach
                    </select>

                    @if(isset($currentIndustry))
                        <img src="{{ asset('storage/' . ($currentIndustry->logo_path ?? 'logo.png')) }}"
                            alt="Logo Indústria" class="rounded-circle" style="height: 40px; width: 40px;">
                        <span class="ms-2">{{ $currentIndustry->fantasy_name ?? ($currentIndustry->nome ?? 'Indústria') }}</span>
                    @else
                        <span class="ms-2">Sem Indústria Selecionada</span>
                    @endif
                </div>

                {{-- Campo de busca e ícones à direita --}}
                <div class="d-flex align-items-center">
                    <form action="{{ route('busca.index') }}" method="GET" class="d-flex me-3" role="search">
                        <input type="text" name="q" class="form-control form-control-sm me-2" placeholder="Buscar..." required>
                        <button class="btn btn-outline-light btn-sm" type="submit"><i class="fas fa-search"></i></button>
                    </form>

                    <div class="dropdown me-3">
                        <a href="#" class="text-white position-relative" id="alertDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ count($notificacoes ?? []) }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alertDropdown">
                            @forelse($notificacoes ?? [] as $notificacao)
                                <li class="dropdown-item small">
                                    <i class="fas fa-info-circle text-secondary me-1"></i> {{ $notificacao }}
                                </li>
                            @empty
                                <li class="dropdown-item text-muted">Sem notificações</li>
                            @endforelse
                        </ul>
                    </div>

                    <a href="#" class="text-white me-3"><i class="fas fa-question-circle"></i></a>

                    <div class="dropdown">
                        <a href="#" class="text-white dropdown-toggle" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user me-2"></i> Minha Conta
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('Sair') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            @yield('content')
        </div>

        {{-- Botão do WhatsApp --}}
        <a href="https://wa.me/55SEUNUMERO" target="_blank" class="whatsapp-btn">
            <i class="fab fa-whatsapp"></i>
        </a>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa os dropdowns
            var dropdowns = document.querySelectorAll('.dropdown-toggle');
            dropdowns.forEach(function(dropdown) {
                new bootstrap.Dropdown(dropdown);
            });

            // Seletor de indústria
            var industrySelect = document.querySelector('select[name="industry"]');
            if (industrySelect) {
                industrySelect.addEventListener('change', function () {
                    fetch(`/settings/industry/${this.value}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => location.reload());
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    <!-- no <head> OU logo antes de fechar </body> -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>




</body>
</html>
