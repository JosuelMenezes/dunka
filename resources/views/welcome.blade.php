<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dunka Representações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="{{ asset('css/style_welcome.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">


</head>
<body>

    <div class="header">
        <img src="{{ route('logomarcas.img', ['file' => 'dunka.png']) }}"
             alt="Logomarca Dunka"
             class="logo mb-3"
             style="max-height: 280px; width: auto; object-fit: contain;">
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
            <h2 class="mb-5 text-center">Catálogos</h2>
            <div class="row justify-content-center">

                {{-- Grupo Multi com múltiplos catálogos --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="catalogo-card h-100 d-flex flex-column justify-content-between p-3 bg-white shadow-sm rounded">
                        <div class="text-center">
                            <img src="{{ route('logomarcas.img', ['file' => 'grupo_multi.png']) }}"
                                 class="catalogo-img mb-3"
                                 alt="Grupo Multi">
                            <div class="catalogo-title">Grupo Multi</div>
                        </div>
                        <div class="dropdown mt-3 text-center">
                            <button class="btn catalogo-btn dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                Visualizar Catálogos
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="{{ route('catalogos.pdf', ['file' => 'catalogo_grupo_multi.pdf']) }}" target="_blank">Mult Principal </a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/multi-saude" target="_blank">Multi Saúde</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/multikids" target="_blank">Multikids</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/multi-giga" target="_blank">Multi GIGA</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/multi-pro" target="_blank">Multi PRO</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/pet" target="_blank">Pet</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/baby" target="_blank">Baby</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/keep" target="_blank">Keep</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/watts" target="_blank">Watts</a></li>
                                <li><a class="dropdown-item" href="https://viewer.ipaper.io/multi/catalogo-2024/qrcodes/atrio" target="_blank">Atrio</a></li>
                                <li><a class="dropdown-item" href="https://acesse.one/Rvg4d" target="_blank">Wellness</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Juliflix com dois catálogos --}}
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="catalogo-card h-100 d-flex flex-column justify-content-between p-3 bg-white shadow-sm rounded">
                        <div class="text-center">
                            <img src="{{ route('logomarcas.img', ['file' => 'juliflix.png']) }}"
                                 class="catalogo-img mb-3"
                                 alt="Juliflix">
                            <div class="catalogo-title">Juliflix</div>
                        </div>
                        <div class="dropdown mt-3 text-center">
                            <button class="btn catalogo-btn dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                Visualizar Catálogos
                            </button>
                            <ul class="dropdown-menu w-100">
                                <li><a class="dropdown-item" href="{{ route('catalogos.pdf', ['file' => 'catalogo_juliflix.pdf']) }}" target="_blank">Juliflix</a></li>
                                <li><a class="dropdown-item" href="{{ route('catalogos.pdf', ['file' => 'catalogo_julian.pdf']) }}" target="_blank">Julian</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Demais catálogos simples (PDF único) --}}
                @php
                    $catalogosSimples = [
                        ['nome' => 'Staedtler', 'pdf' => 'catalogo_staedtler.pdf', 'logo' => 'staedtler.png'],
                        ['nome' => 'Newpen', 'pdf' => 'catalogo_newpen.pdf', 'logo' => 'newpen.png'],
                        ['nome' => 'Cia da Meia', 'pdf' => 'catalogo_cia_da_meia.pdf', 'logo' => 'cia_da_meia.png'],
                        ['nome' => 'Cola Iris', 'pdf' => 'catalogo_cola_iris.pdf', 'logo' => 'cola_iris.png'],
                    ];
                @endphp

                @foreach($catalogosSimples as $cat)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="catalogo-card h-100 d-flex flex-column justify-content-between p-3 bg-white shadow-sm rounded">
                            <div class="text-center">
                                <img src="{{ route('logomarcas.img', ['file' => $cat['logo']]) }}"
                                     class="catalogo-img mb-3"
                                     alt="Logo {{ $cat['nome'] }}">
                                <div class="catalogo-title">{{ $cat['nome'] }}</div>
                            </div>
                            <a href="{{ route('catalogos.pdf', ['file' => $cat['pdf']]) }}" target="_blank"
                               class="btn catalogo-btn btn-sm mt-3">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Visualizar PDF
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>



    <div class="section vendedores container">
        <h2 class="text-center mb-5">Nossos Vendedores</h2>
        <div class="row justify-content-center">
            @php
                $vendedores = [
                    ['nome' => 'Josuel Menezes', 'telefone' => '61 99119-0352', 'email' => 'josuel@dunka.com.br'],
                    ['nome' => 'Samuel', 'telefone' => '61 98199-5506', 'email' => 'samuel@dunka.com.br'],
                    ['nome' => 'Rodrigo', 'telefone' => '61 99860-7252', 'email' => 'rodrigo@dunka.com.br'],
                    ['nome' => 'Estefany', 'telefone' => '61 99208-6827', 'email' => 'estefany@dunka.com.br'],
                ];
            @endphp

            @foreach($vendedores as $v)
                @php
                    $foto = 'storage/vendedores/' . strtolower(str_replace(' ', '_', $v['nome'])) . '.jpg';
                @endphp

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card text-center shadow-sm border-0 rounded-4 h-100">
                        <img src="{{ asset($foto) }}"
                             onerror="this.onerror=null;this.src='{{ asset('images/default.jpg') }}';"
                             class="card-img-top vendedor-img mx-auto mt-3"
                             alt="Foto de {{ $v['nome'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $v['nome'] }}</h5>
                            <p>{{ $v['telefone'] }}</p>
                            @if($v['email'])
                                <p class="text-muted small">{{ $v['email'] }}</p>
                            @endif
                            <a href="https://wa.me/55{{ preg_replace('/\D/', '', $v['telefone']) }}" target="_blank"
                               class="btn btn-whatsapp btn-sm">
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
