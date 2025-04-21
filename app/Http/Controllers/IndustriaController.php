<?php

namespace App\Http\Controllers;

use App\Models\Industria;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class IndustriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $industrias = Industria::withCount('produtos')
            ->orderBy('nome')
            ->paginate(8);

        return view('industrias.index', compact('industrias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('industrias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'inscricao_estadual' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'endereco' => 'nullable|string',
            'comissao' => 'nullable|numeric|min:0|max:100',
            'logo' => 'nullable|image|max:2048',
        ]);

        $dados = $request->except('logo');

        // Processar o upload da logomarca
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoNome = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();

            // Salvar imagem sem redimensionamento
            $logo->storeAs('industrias', $logoNome, 'public');
            $dados['logo'] = 'industrias/' . $logoNome;
        }

        $industria = Industria::create($dados);

        return redirect()->route('industrias.index')
            ->with('success', 'Indústria cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $industria = Industria::findOrFail($id);
        $produtos_count = Produto::where('industria_id', $id)->count();

        return view('industrias.show', compact('industria', 'produtos_count'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $industria = Industria::findOrFail($id);

        return view('industrias.edit', compact('industria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $industria = Industria::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'razao_social' => 'required|string|max:255',
            'cnpj' => 'nullable|string|max:18',
            'inscricao_estadual' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'endereco' => 'nullable|string',
            'comissao' => 'nullable|numeric|min:0|max:100',
            'logo' => 'nullable|image|max:2048',
        ]);

        $dados = $request->except(['logo', '_token', '_method']);

        // Processar o upload da logomarca
        if ($request->hasFile('logo')) {
            // Remover logo antiga se existir
            if ($industria->logo && Storage::disk('public')->exists($industria->logo)) {
                Storage::disk('public')->delete($industria->logo);
            }

            $logo = $request->file('logo');
            $logoNome = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();

            // Salvar imagem sem redimensionamento
            $logo->storeAs('industrias', $logoNome, 'public');
            $dados['logo'] = 'industrias/' . $logoNome;
        }

        $industria->update($dados);

        return redirect()->route('industrias.index')
            ->with('success', 'Indústria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $industria = Industria::findOrFail($id);

        // Verificar se existem produtos associados
        $produtosCount = Produto::where('industria_id', $id)->count();

        if ($produtosCount > 0) {
            return redirect()->route('industrias.index')
                ->with('error', 'Não é possível excluir esta indústria pois existem produtos associados a ela.');
        }

        // Remover logo se existir
        if ($industria->logo && Storage::disk('public')->exists($industria->logo)) {
            Storage::disk('public')->delete($industria->logo);
        }

        $industria->delete();

        return redirect()->route('industrias.index')
            ->with('success', 'Indústria excluída com sucesso!');
    }

    /**
     * Seleciona a indústria atual.
     */
    public function selecionarIndustria(string $id)
    {
        $industria = Industria::findOrFail($id);

        // Armazenar na sessão
        Session::put('industria_atual', $industria->id);
        Session::put('industria_atual_nome', $industria->nome);

        return redirect()->back()->with('success', "Indústria {$industria->nome} selecionada com sucesso!");
    }

    /**
     * Página para importar produtos.
     */
    public function importarProdutos(string $id)
    {
        $industria = Industria::findOrFail($id);
        $produtos_count = Produto::where('industria_id', $id)->count();

        return view('industrias.importar-produtos', compact('industria', 'produtos_count'));
    }

    /**
     * Gerar modelo de planilha para importação.
     */
    public function gerarModeloPlanilha(string $id)
    {
        $industria = Industria::findOrFail($id);

        // Criar uma nova planilha
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Definir cabeçalhos
        $sheet->setCellValue('A1', 'codigo');
        $sheet->setCellValue('B1', 'nome');
        $sheet->setCellValue('C1', 'descricao');
        $sheet->setCellValue('D1', 'preco');
        $sheet->setCellValue('E1', 'estoque');
        $sheet->setCellValue('F1', 'ean');
        $sheet->setCellValue('G1', 'ncm');
        $sheet->setCellValue('H1', 'variacao_ipi');

        // Ajustar largura das colunas
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(50);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);

        // Estilizar cabeçalhos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E0E0E0',
                ],
            ],
        ];

        $sheet->getStyle('A1:H1')->applyFromArray($styleArray);

        // Adicionar exemplos
        $sheet->setCellValue('A2', 'ABC123');
        $sheet->setCellValue('B2', 'Nome do Produto');
        $sheet->setCellValue('C2', 'Descrição detalhada do produto');
        $sheet->setCellValue('D2', '99.90');
        $sheet->setCellValue('E2', '10');
        $sheet->setCellValue('F2', '7891234567890');
        $sheet->setCellValue('G2', '12345678');
        $sheet->setCellValue('H2', '12.00');

        // Adicionar mais um exemplo
        $sheet->setCellValue('A3', 'DEF456');
        $sheet->setCellValue('B3', 'Outro Produto');
        $sheet->setCellValue('C3', 'Outra descrição');
        $sheet->setCellValue('D3', '149.90');
        $sheet->setCellValue('E3', '5');
        $sheet->setCellValue('F3', '7899876543210');
        $sheet->setCellValue('G3', '87654321');
        $sheet->setCellValue('H3', '0.00');

        // Criar um escritor
        $writer = new Xlsx($spreadsheet);

        // Salvar o arquivo temporariamente
        $filename = 'modelo_produtos_' . Str::slug($industria->nome) . '_' . date('Ymd') . '.xlsx';
        $tempPath = storage_path('app/temp/' . $filename);

        // Garantir que o diretório exista
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $writer->save($tempPath);

        // Enviar o arquivo como download
        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Processar a importação de produtos.
     */
    public function processarImportacao(Request $request, string $id)
    {
        $industria = Industria::findOrFail($id);

        $request->validate([
            'arquivo_excel' => 'required|file|mimes:xlsx,xls,csv',
            'atualizar_existentes' => 'nullable|boolean',
            'primeira_linha_cabecalho' => 'nullable|boolean',
            'sobrescrever_estoque' => 'nullable|boolean',
        ]);

        // Inicializar contadores e arrays para mensagens
        $totalImportados = 0;
        $totalAtualizados = 0;
        $totalIgnorados = 0;
        $erros = [];
        $warnings = [];

        try {
            // Obter o arquivo
            $arquivo = $request->file('arquivo_excel');

            // Determinar tipo do arquivo
            $extension = $arquivo->getClientOriginalExtension();
            $readerType = match($extension) {
                'csv' => 'Csv',
                'xls' => 'Xls',
                default => 'Xlsx',
            };

            // Criar o leitor apropriado
            $reader = IOFactory::createReader($readerType);
            $spreadsheet = $reader->load($arquivo->getPathname());

            // Obter a primeira planilha
            $worksheet = $spreadsheet->getActiveSheet();

            // Mapear os cabeçalhos se a primeira linha for cabeçalho
            $primeiraLinhaCabecalho = $request->has('primeira_linha_cabecalho');
            $headers = [];

            if ($primeiraLinhaCabecalho) {
                $headerRow = $worksheet->getRowIterator(1)->current();
                $cellIterator = $headerRow->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $colIndex = 0;
                foreach ($cellIterator as $cell) {
                    $headers[$colIndex] = strtolower(trim($cell->getValue()));
                    $colIndex++;
                }

                // Verificar cabeçalhos mínimos
                $requiredHeaders = ['codigo', 'nome', 'preco'];
                $missingHeaders = array_diff($requiredHeaders, $headers);

                if (!empty($missingHeaders)) {
                    return redirect()->back()->with('error',
                        'Cabeçalhos obrigatórios ausentes: ' . implode(', ', $missingHeaders));
                }
            }

            // Determinar por qual linha começar
            $startRow = $primeiraLinhaCabecalho ? 2 : 1;

            // Processar as linhas
            foreach ($worksheet->getRowIterator($startRow) as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowData = [];
                $colIndex = 0;

                foreach ($cellIterator as $cell) {
                    $value = trim($cell->getValue());

                    if ($primeiraLinhaCabecalho && isset($headers[$colIndex])) {
                        $rowData[$headers[$colIndex]] = $value;
                    } else {
                        $rowData[$colIndex] = $value;
                    }

                    $colIndex++;
                }

                // Se usar cabeçalhos, verificar campos obrigatórios
                if ($primeiraLinhaCabecalho) {
                    // Pular linhas vazias
                    if (empty($rowData['codigo']) && empty($rowData['nome'])) {
                        continue;
                    }

                    // Verificar campos obrigatórios
                    if (empty($rowData['codigo'])) {
                        $warnings[] = "Linha {$row->getRowIndex()}: Código não informado. Linha ignorada.";
                        $totalIgnorados++;
                        continue;
                    }

                    if (empty($rowData['nome'])) {
                        $warnings[] = "Linha {$row->getRowIndex()}: Nome não informado. Linha ignorada.";
                        $totalIgnorados++;
                        continue;
                    }

                    if (empty($rowData['preco']) || !is_numeric($rowData['preco'])) {
                        $warnings[] = "Linha {$row->getRowIndex()}: Preço inválido. Linha ignorada.";
                        $totalIgnorados++;
                        continue;
                    }

                    // Formatar campos
                    $dados = [
                        'codigo' => $rowData['codigo'],
                        'nome' => $rowData['nome'],
                        'descricao' => $rowData['descricao'] ?? null,
                        'preco' => floatval($rowData['preco']),
                        'estoque' => isset($rowData['estoque']) && is_numeric($rowData['estoque']) ? intval($rowData['estoque']) : 0,
                        'ean' => $rowData['ean'] ?? null,
                        'ncm' => $rowData['ncm'] ?? null,
                        'variacao_ipi' => isset($rowData['variacao_ipi']) && is_numeric($rowData['variacao_ipi'])
                            ? floatval($rowData['variacao_ipi']) : 0,
                        'industria_id' => $industria->id,
                    ];
                } else {
                    // Sem cabeçalhos, usar índices fixos
                    // Pular linhas vazias
                    if (empty($rowData[0]) && empty($rowData[1])) {
                        continue;
                    }

                    // Verificar campos obrigatórios
                    if (empty($rowData[0])) {
                        $warnings[] = "Linha {$row->getRowIndex()}: Código não informado. Linha ignorada.";
                        $totalIgnorados++;
                        continue;
                    }

                    if (empty($rowData[1])) {
                        $warnings[] = "Linha {$row->getRowIndex()}: Nome não informado. Linha ignorada.";
                        $totalIgnorados++;
                        continue;
                    }

                    if (!isset($rowData[3]) || !is_numeric($rowData[3])) {
                        $warnings[] = "Linha {$row->getRowIndex()}: Preço inválido. Linha ignorada.";
                        $totalIgnorados++;
                        continue;
                    }

                    // Formatar campos
                    $dados = [
                        'codigo' => $rowData[0],
                        'nome' => $rowData[1],
                        'descricao' => $rowData[2] ?? null,
                        'preco' => floatval($rowData[3]),
                        'estoque' => isset($rowData[4]) && is_numeric($rowData[4]) ? intval($rowData[4]) : 0,
                        'ean' => $rowData[5] ?? null,
                        'ncm' => $rowData[6] ?? null,
                        'variacao_ipi' => isset($rowData[7]) && is_numeric($rowData[7])
                            ? floatval($rowData[7]) : 0,
                        'industria_id' => $industria->id,
                    ];
                }

                // Validar dados
                $validator = Validator::make($dados, [
                    'codigo' => 'required|string|max:100',
                    'nome' => 'required|string|max:255',
                    'descricao' => 'nullable|string',
                    'preco' => 'required|numeric|min:0',
                    'estoque' => 'nullable|integer|min:0',
                    'ean' => 'nullable|string|max:20',
                    'ncm' => 'nullable|string|max:20',
                    'variacao_ipi' => 'nullable|numeric|min:0|max:100',
                    'industria_id' => 'required|exists:industrias,id',
                ]);

                if ($validator->fails()) {
                    $erros = $validator->errors()->all();
                    $errorMessage = implode(', ', $erros);
                    $warnings[] = "Linha {$row->getRowIndex()}: {$errorMessage}. Linha ignorada.";
                    $totalIgnorados++;
                    continue;
                }

                // Verificar se o produto já existe
                $produto = Produto::where('codigo', $dados['codigo'])
                    ->where('industria_id', $industria->id)
                    ->first();

                if ($produto) {
                    // Se não for para atualizar, pular
                    if (!$request->has('atualizar_existentes')) {
                        $totalIgnorados++;
                        continue;
                    }

                    // Atualizando o produto
                    if (!$request->has('sobrescrever_estoque')) {
                        // Se não for para sobrescrever estoque, somar
                        $dados['estoque'] = $produto->estoque + $dados['estoque'];
                    }

                    $produto->update($dados);
                    $totalAtualizados++;
                } else {
                    // Criar novo produto
                    Produto::create($dados);
                    $totalImportados++;
                }
            }

            // Retornar mensagem de sucesso
            $message = "Importação concluída com sucesso! ";
            $message .= "Produtos novos: {$totalImportados}. ";
            $message .= "Produtos atualizados: {$totalAtualizados}. ";

            if ($totalIgnorados > 0) {
                $message .= "Produtos ignorados: {$totalIgnorados}.";
                return redirect()->back()
                    ->with('success', $message)
                    ->with('warnings', $warnings);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar o arquivo: ' . $e->getMessage());
        }
    }

    /**
     * Página para importar imagens de produtos.
     */
    public function importarImagens(string $id)
    {
        $industria = Industria::findOrFail($id);

        // Contagem de produtos sem imagem
        $produtos_sem_imagem = Produto::where('industria_id', $id)
            ->whereNull('foto')
            ->count();

        return view('industrias.importar-imagens', compact('industria', 'produtos_sem_imagem'));
    }

    /**
     * Gerar lista de produtos sem imagem.
     */
    public function listaProdutosSemImagem(string $id)
    {
        $industria = Industria::findOrFail($id);

        // Buscar produtos sem imagem
        $produtos = Produto::where('industria_id', $id)
            ->whereNull('foto')
            ->select('id', 'codigo', 'nome')
            ->orderBy('codigo')
            ->get();

        // Criar uma nova planilha
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Definir cabeçalhos
        $sheet->setCellValue('A1', 'Código');
        $sheet->setCellValue('B1', 'Nome do Produto');
        $sheet->setCellValue('C1', 'Nome do Arquivo de Imagem');

        // Ajustar largura das colunas
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(30);

        // Estilizar cabeçalhos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E0E0E0',
                ],
            ],
        ];

        $sheet->getStyle('A1:C1')->applyFromArray($styleArray);

        // Adicionar dados
        $row = 2;
        foreach ($produtos as $produto) {
            $sheet->setCellValue('A' . $row, $produto->codigo);
            $sheet->setCellValue('B' . $row, $produto->nome);
            $sheet->setCellValue('C' . $row, $produto->codigo . '.jpg');
            $row++;
        }

        // Criar um escritor
        $writer = new Xlsx($spreadsheet);

        // Salvar o arquivo temporariamente
        $filename = 'produtos_sem_imagem_' . Str::slug($industria->nome) . '_' . date('Ymd') . '.xlsx';
        $tempPath = storage_path('app/temp/' . $filename);

        // Garantir que o diretório exista
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $writer->save($tempPath);

        // Enviar o arquivo como download
        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Processar imagens de produtos enviadas.
     */
    public function processarImagens(Request $request, string $id)
    {
        $industria = Industria::findOrFail($id);

        $request->validate([
            'imagens' => 'required|array',
            'imagens.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'redimensionar' => 'nullable|boolean',
        ]);

        // Verificar se existem imagens
        if (!$request->hasFile('imagens')) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma imagem enviada.',
            ]);
        }

        // Inicializar contadores e arrays
        $total = count($request->file('imagens'));
        $processadas = 0;
        $atualizados = 0;
        $ignoradas = [];

        // Processar cada imagem
        foreach ($request->file('imagens') as $imagem) {
            // Verificar extensão
            $extensao = $imagem->getClientOriginalExtension();
            if (!in_array(strtolower($extensao), ['jpg', 'jpeg', 'png'])) {
                $ignoradas[] = [
                    'arquivo' => $imagem->getClientOriginalName(),
                    'motivo' => 'Formato de arquivo inválido.',
                ];
                continue;
            }

            // Obter o código do produto a partir do nome do arquivo
            $nomeArquivo = $imagem->getClientOriginalName();
            $codigo = pathinfo($nomeArquivo, PATHINFO_FILENAME);

            // Buscar o produto pelo código
            $produto = Produto::where('codigo', $codigo)
                ->where('industria_id', $industria->id)
                ->first();

            if (!$produto) {
                $ignoradas[] = [
                    'arquivo' => $nomeArquivo,
                    'motivo' => 'Produto não encontrado com este código.',
                ];
                continue;
            }

            // Processar a imagem
            try {
                // Remover imagem antiga se existir
                if ($produto->foto && Storage::disk('public')->exists($produto->foto)) {
                    Storage::disk('public')->delete($produto->foto);
                }

                // Gerar nome único
                $imagemNome = $codigo . '_' . time() . '.' . $extensao;

                // Salvar a imagem sem redimensionamento
                $imagem->storeAs('produtos', $imagemNome, 'public');

                // Atualizar produto
                $produto->update(['foto' => 'produtos/' . $imagemNome]);

                $processadas++;
                $atualizados++;

            } catch (\Exception $e) {
                $ignoradas[] = [
                    'arquivo' => $nomeArquivo,
                    'motivo' => 'Erro ao processar imagem: ' . $e->getMessage(),
                ];
                continue;
            }
        }

        // Retornar resposta JSON
        return response()->json([
            'success' => true,
            'total' => $total,
            'processadas' => $processadas,
            'atualizados' => $atualizados,
            'ignoradas' => $ignoradas,
        ]);
    }
}
