<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Bem-vindo à Dunka Representações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .header {
            background-color: #222;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .logo {
            max-height: 280px;
        }

        .section {
            padding: 40px 20px;
        }

        .vendedores .card {
    margin: 15px 0;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.vendedores .card-img-top {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    margin: 20px auto 10px auto;
    border: 3px solid #198754;
}
.vendedores .card-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.vendedores .card-body p {
    margin: 0;
    font-size: 0.9rem;
    color: #555;
}

.vendedores .btn-whatsapp {
    margin-top: 10px;
    background-color: #25D366;
    color: white;
    border: none;
}

        .btn-sistema {
            margin-top: 30px;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #222;
            color: white;
        }

        .catalogo-card {
    border: none;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: transform 0.2s;
    margin-bottom: 20px;
    background: white;
}

.catalogo-card:hover {
    transform: translateY(-5px);
}

.catalogo-img {
    max-height: 100px;
    object-fit: contain;
    padding: 20px;
}

.catalogo-title {
    font-size: 1.1rem;
    font-weight: bold;
}

.catalogo-btn {
    background-color: #0d6efd;
    color: white;
    margin: 10px auto 20px auto;
    border-radius: 30px;
}
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ asset('storage/logomarcas/dunka.png') }}" alt="Logomarca Dunka" class="logo mb-3">
      <!--  <h1>Dunka Representações</h1>-->
        <p>Representação Comercial em Brasília - DF</p>
    </div>

    <div class="section container">
        <h2 class="text-center mb-5">Quem Somos</h2>
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <h4 class="mb-3">Representação Comercial de Excelência</h4>
                <p>
                    A <strong>Dunka Representações</strong> é especializada em representar marcas líderes do mercado no segmento de papelaria, escritório, presentes e muito mais. Atendemos com exclusividade a região de <strong>Brasília - DF</strong>, oferecendo suporte comercial, visitas personalizadas e relacionamento próximo com nossos clientes.
                </p>
                <p>
                    Nosso foco é conectar indústrias a lojistas, promovendo crescimento mútuo com eficiência, comprometimento e transparência.
                </p>
            </div>
            <div class="col-md-6 mb-4">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h5 class="mb-3"><i class="bi bi-building"></i> Indústrias que representamos:</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-check-circle text-success me-2"></i> Grupo Multi</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> Staedtler</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> Newpen</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> Cia da Meia</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> Juliflix</li>
                        <li><i class="bi bi-check-circle text-success me-2"></i> Cola Iris</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="section bg-light">
        <div class="container">
            <h2>Catálogos</h2>
            <div class="row">
                @php
                    $catalogos = [
                        ['nome' => 'Grupo Multi', 'pdf' => 'catalogo_grupo_multi.pdf', 'logo' => 'grupo_multi.png'],
                        ['nome' => 'Staedtler', 'pdf' => 'catalogo_staedtler.pdf', 'logo' => 'staedtler.png'],
                        ['nome' => 'Newpen', 'pdf' => 'catalogo_newpen.pdf', 'logo' => 'newpen.png'],
                        ['nome' => 'Cia da Meia', 'pdf' => 'catalogo_cia_da_meia.pdf', 'logo' => 'cia_da_meia.png'],
                        ['nome' => 'Juliflix', 'pdf' => 'catalogo_juliflix.pdf', 'logo' => 'juliflix.png'],
                        ['nome' => 'Cola Iris', 'pdf' => 'catalogo_cola_iris.pdf', 'logo' => 'cola_iris.png'],
                    ];
                @endphp

                @foreach($catalogos as $cat)
                    <div class="col-md-4">
                        <div class="card catalogo-card">
                            <img src="{{ asset('storage/logomarcas/' . $cat['logo']) }}" class="catalogo-img mx-auto d-block" alt="Logo {{ $cat['nome'] }}">
                            <div class="card-body">
                                <div class="catalogo-title">{{ $cat['nome'] }}</div>
                                <a href="{{ asset('storage/catalogos/' . $cat['pdf']) }}" target="_blank" class="btn catalogo-btn">
                                    Visualizar PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    <div class="section vendedores container">
        <h2>Nossos Vendedores</h2>
        <div class="row">
            @php
                $vendedores = [
                    ['nome' => 'Josuel Menezes', 'telefone' => '61 99119-0352', 'email' => 'josuel@dunka.com.br'],
                    ['nome' => 'Samuel', 'telefone' => '61 98199-5506', 'email' => 'samuel@dunka.com.br'],
                    ['nome' => 'Rodrigo', 'telefone' => '61 99860-7252', 'email' => 'rodrigo@dunka.com.br'],
                    ['nome' => 'Estefany', 'telefone' => '61 99208-6827', 'email' => 'estefany@dunka.com.br'],

                ];
            @endphp

@foreach($vendedores as $v)
<div class="col-md-3">
    <div class="card text-center">
        <img src="{{ asset('storage/vendedores/' . strtolower(str_replace(' ', '_', $v['nome'])) . '.jpg') }}"
             class="card-img-top"
             alt="Foto de {{ $v['nome'] }}"
             onerror="this.onerror=null;this.src='{{ asset('images/default.jpg') }}';">
        <div class="card-body">
            <h5 class="card-title">{{ $v['nome'] }}</h5>
            <p>{{ $v['telefone'] }}</p>
            @if($v['email'])
                <p>{{ $v['email'] }}</p>
            @endif
            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $v['telefone']) }}" target="_blank" class="btn btn-whatsapp">
                <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
        </div>
    </div>
</div>
@endforeach

        </div>
    </div>

    <div class="section text-center">
        <a href="http://dunka.com.br/login" class="btn btn-primary btn-lg btn-sistema">Acessar Sistema Dunka GC</a>
    </div>

    <footer>
        <p>&copy; {{ date('Y') }} Dunka Representações - Brasília, DF</p>
    </footer>

</body>
</html>
