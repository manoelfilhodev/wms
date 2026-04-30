<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demanda;
use App\Models\DemandaDistribuicao;
use App\Models\DemandaItem;
use App\Models\User;
use App\Exports\DemandasExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DemandaHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DemandaController extends Controller
{
    private const SKUS_BLOQUEADOS = [
        '1101', '1163', '1112', '1312', '22291', '22298', '22307', '22308', '21842', '40285', '22297',
    ];

    // Listagem
    public function index(Request $request)
{
    $query = Demanda::query();

    if ($request->filled('fo')) {
        $query->where('fo', 'like', "%{$request->fo}%");
    }

    if ($request->filled('transportadora')) {
        $query->where('transportadora', 'like', "%{$request->transportadora}%");
    }

    if ($request->filled('cliente')) {
        $query->where('cliente', 'like', "%{$request->cliente}%");
    }

    if ($request->filled('status')) {
        if ($request->status === 'SEPARADO_PARCIAL') {
            $query->whereNotNull('separacao_finalizada_em')
                ->where('separacao_resultado', 'PARCIAL');
        } elseif ($request->status === 'SEPARADO') {
            $query->whereNotNull('separacao_finalizada_em')
                ->where('separacao_resultado', 'COMPLETA');
        } else {
            $query->where('status', $request->status);
        }
    }

    if ($request->boolean('somente_sobra')) {
        $query->where('possui_sobra', true);
        if (!$request->filled('data_inicio') && !$request->filled('data_fim')) {
            $hoje = Carbon::today()->format('Y-m-d');
            $request->merge([
                'data_inicio' => $hoje,
                'data_fim' => $hoje,
            ]);
        }
    }

    if ($request->filled('data_inicio') && $request->filled('data_fim')) {
        if ($request->data_inicio === $request->data_fim) {
            $query->whereDate('created_at', $request->data_inicio);
        } else {
            $query->whereBetween('created_at', [
                Carbon::parse($request->data_inicio)->startOfDay(),
                Carbon::parse($request->data_fim)->endOfDay(),
            ]);
        }
    }

    $demandas = $query
        ->with(['separador', 'separadores', 'distribuicoes'])
        ->withSum(['itens as total_pecas_picking' => function ($q) {
            $q->where('sobra', '>', 0);
        }], 'sobra')
        ->withSum('distribuicoes as total_pecas_distribuidas', 'quantidade_pecas')
        ->orderBy('created_at', 'desc')
        ->paginate(20);
    $separadores = User::query()
        ->where('status', 1)
        ->orderBy('nome')
        ->get(['id_user', 'nome']);

    $modoOperacional = $request->boolean('somente_sobra');

    return view('demandas.index', compact('demandas', 'modoOperacional', 'separadores'));
}


    // Formulário de criação
    public function create()
    {
        return view('demandas.create');
    }

    // Salvar nova demanda
    public function store(Request $request)
    {
        $request->validate([
            'fo' => 'required|string|max:50',
            'cliente' => 'required|string|max:150',
            'tipo' => 'required|in:RECEBIMENTO,EXPEDICAO',
        ]);

        Demanda::create([
            'fo' => $request->fo,
            'cliente' => $request->cliente,
            'transportadora' => $request->transportadora,
            'doca' => $request->doca,
            'tipo' => $request->tipo,
            'quantidade' => $request->quantidade,
            'peso' => $request->peso,
            'valor_carga' => $request->valor_carga,
            'hora_agendada' => $request->hora_agendada,
            'entrada' => $request->entrada,
            'saida' => $request->saida,
            'status' => 'GERAR', // sempre inicia em GERAR
        ]);

        return redirect()->route('demandas.index')->with('success', 'Demanda lançada com sucesso!');
    }
    
    // Exibir formulário de edição
public function edit($id)
{
    $demanda = Demanda::findOrFail($id);
    return view('demandas.edit', compact('demanda'));
}

// Atualizar demanda
public function update(Request $request, $id)
{
    $request->validate([
        'fo'              => 'required|string|max:50',
        'cliente'         => 'required|string|max:150',
        'tipo'            => 'required|in:RECEBIMENTO,EXPEDICAO',
        'transportadora'  => 'nullable|string|max:150',
        'doca'            => 'nullable|string|max:10',
        'quantidade'      => 'nullable|integer|min:0',
        'peso'            => 'nullable|numeric',
        'valor_carga'     => 'nullable|numeric',
        'hora_agendada'   => 'nullable',
        'entrada'         => 'nullable',
        'saida'           => 'nullable',
        'veiculo'         => 'nullable|string|max:50',
        'modelo_veicular' => 'nullable|string|max:150',
        'motorista'       => 'nullable|string|max:150',
    ]);

    $demanda = Demanda::findOrFail($id);

    $demanda->update([
        'fo'              => $request->fo,
        'cliente'         => $request->cliente,
        'transportadora'  => $request->transportadora,
        'doca'            => $request->doca,
        'tipo'            => $request->tipo,
        'quantidade'      => $request->quantidade ?? 0,
        'peso'            => $request->peso ?? 0,
        'valor_carga'     => $request->valor_carga ?? 0,
        'hora_agendada'   => $request->hora_agendada,
        'entrada'         => $request->entrada,
        'saida'           => $request->saida,
        'veiculo'         => $request->veiculo,
        'modelo_veicular' => $request->modelo_veicular,
        'motorista'       => $request->motorista,
        // status não editamos aqui para não quebrar o fluxo
    ]);

    return redirect()->route('demandas.index')->with('success', 'Demanda atualizada com sucesso!');
}


public function destroy($id)
{
    $demanda = Demanda::findOrFail($id);
    $demanda->delete();

    return redirect()->route('demandas.index')->with('success', 'Demanda excluída com sucesso!');
}

public function export(Request $request)
{
    return Excel::download(new DemandasExport($request), 'demandas_filtradas.xlsx');
}

public function updateStatus(Request $request, $id)
{
    $request->validate(['status' => 'required|string']);

    $demanda = Demanda::findOrFail($id);
    $demanda->status = $request->status;
    $demanda->save();

    // Salvar histórico
    DemandaHistory::create([
        'demanda_id' => $demanda->id,
        'status' => $request->status,
        'changed_by' => auth()->user()->id_user ?? null,
    ]);

    return back()->with('success', "Status da FO {$demanda->fo} atualizado!");
}

public function updateMultiple(Request $request)
{
    $request->validate([
        'status' => 'required|string',
        'ids' => 'required|array'
    ]);

    foreach ($request->ids as $id) {
        $demanda = Demanda::find($id);
        if ($demanda) {
            $demanda->update(['status' => $request->status]);

            DemandaHistory::create([
                'demanda_id' => $demanda->id,
                'status' => $request->status,
                'changed_by' => auth()->user()->id_user ?? null,
            ]);
        }
    }

    return back()->with('success', 'Status atualizado em lote com sucesso!');
}

public function import(Request $request)
{
    if (!$request->filled('planilha')) {
        return back()->with('error', 'Nenhum dado foi enviado.');
    }

    $linhas = preg_split("/\r\n|\n|\r/", trim($request->planilha));
    if (count($linhas) < 2) {
        return back()->with('error', 'Planilha inválida.');
    }

    $cabecalho = preg_split("/\t+/", trim($linhas[0]));
    $mapa = $this->mapearCabecalho($cabecalho);

    if (!$mapa['is_sap']) {
        return back()->with('error', 'Formato não reconhecido. Use a exportação SAP com colunas Transporte, Material e Sobra.');
    }

    $resumoPorDt = [];
    $itensImportados = 0;
    $itensIgnoradosBloqueio = 0;

    DB::transaction(function () use ($linhas, $mapa, &$resumoPorDt, &$itensImportados, &$itensIgnoradosBloqueio) {
        foreach ($linhas as $index => $linha) {
            if ($index === 0 || trim($linha) === '') {
                continue;
            }

            $colunas = preg_split("/\t+/", trim($linha));
            $dt = trim($colunas[$mapa['transporte']] ?? '');
            $skuOriginal = trim($colunas[$mapa['material']] ?? '');

            if ($dt === '' || $skuOriginal === '') {
                continue;
            }

            $skuNormalizado = $this->normalizarSku($skuOriginal);
            $isBloqueado = in_array($skuNormalizado, self::SKUS_BLOQUEADOS, true);
            if ($isBloqueado) {
                $itensIgnoradosBloqueio++;
                continue;
            }

            $sobra = $this->converteNumero($colunas[$mapa['sobra']] ?? '0');
            $temSobra = $sobra > 0;

            if (!isset($resumoPorDt[$dt])) {
                $demanda = Demanda::updateOrCreate(
                    ['fo' => $dt],
                    [
                        'cliente' => $colunas[$mapa['nome']] ?? null,
                        'transportadora' => $colunas[$mapa['transportadora']] ?? null,
                        'tipo' => 'EXPEDICAO',
                        'status' => 'A_SEPARAR',
                        'hora_agendada' => null,
                        'total_itens' => 0,
                        'total_itens_com_sobra' => 0,
                        'possui_sobra' => false,
                    ]
                );

                DemandaItem::where('demanda_id', $demanda->id)->delete();
                $resumoPorDt[$dt] = ['demanda_id' => $demanda->id, 'total' => 0, 'com_sobra' => 0];
            }

            DemandaItem::create([
                'demanda_id' => $resumoPorDt[$dt]['demanda_id'],
                'sku' => $skuOriginal,
                'sku_normalizado' => $skuNormalizado,
                'descricao' => $colunas[$mapa['descricao']] ?? null,
                'unidade_medida' => $colunas[$mapa['unidade']] ?? null,
                'sobra' => $sobra,
                'bloqueado' => false,
            ]);

            $resumoPorDt[$dt]['total']++;
            $resumoPorDt[$dt]['com_sobra'] += $temSobra ? 1 : 0;
            $itensImportados++;
        }

        foreach ($resumoPorDt as $dt => $resumo) {
            Demanda::where('id', $resumo['demanda_id'])->update([
                'total_itens' => $resumo['total'],
                'total_itens_com_sobra' => $resumo['com_sobra'],
                'possui_sobra' => $resumo['com_sobra'] > 0,
                'status' => $resumo['com_sobra'] > 0 ? 'A_SEPARAR' : 'GERAR',
            ]);
        }
    });

    $dtsComSobra = collect($resumoPorDt)->filter(fn($r) => $r['com_sobra'] > 0)->count();
    return back()->with(
        'success',
        "Importação concluída. Itens válidos: {$itensImportados}. Itens bloqueados: {$itensIgnoradosBloqueio}. DTs com sobra: {$dtsComSobra}."
    );
}

private function converteNumero($valor)
{
    if (!$valor || trim($valor) === '') {
        return 0;
    }

    // converte 10.291,34 → 10291.34
    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);

    return (float) $valor;
}

private function normalizarSku(?string $sku): string
{
    $sku = preg_replace('/\D+/', '', (string) $sku);
    $sku = ltrim($sku, '0');
    return $sku === '' ? '0' : $sku;
}

private function mapearCabecalho(array $cabecalho): array
{
    $normalizados = [];
    foreach ($cabecalho as $idx => $coluna) {
        $k = mb_strtolower(trim($coluna));
        $normalizados[$k] = $idx;
    }

    return [
        'is_sap' => isset($normalizados['transporte'], $normalizados['material'], $normalizados['sobra']),
        'transporte' => $normalizados['transporte'] ?? null,
        'transportadora' => $normalizados['transportadora'] ?? null,
        'material' => $normalizados['material'] ?? null,
        'sobra' => $normalizados['sobra'] ?? null,
        'unidade' => $normalizados['unid.medida básica'] ?? null,
        'descricao' => $normalizados['texto breve material'] ?? null,
        'nome' => $normalizados['nome'] ?? null,
    ];
}

public function operacional(Request $request)
{
    $request->merge(['somente_sobra' => 1]);
    return $this->index($request);
}

public function distribuirDt(Request $request, $id)
{
    $request->validate([
        'separador_nome' => 'required|string|max:150',
        'quantidade_pecas' => 'required|integer|min:1',
    ]);

    $demanda = Demanda::withSum(['itens as total_pecas_picking' => function ($q) {
        $q->where('sobra', '>', 0);
    }], 'sobra')->withSum('distribuicoes as total_pecas_distribuidas', 'quantidade_pecas')->findOrFail($id);

    $totalPicking = (int) round((float) ($demanda->total_pecas_picking ?? 0));
    $distribuido = (int) ($demanda->total_pecas_distribuidas ?? 0);
    $restante = max(0, $totalPicking - $distribuido);
    $qtd = (int) $request->quantidade_pecas;

    if ($totalPicking <= 0) {
        return back()->with('error', "A DT {$demanda->fo} não possui peças de picking para distribuir.");
    }
    if ($qtd > $restante) {
        return back()->with('error', "Quantidade inválida. Restante disponível para distribuição: {$restante} peças.");
    }

    $separadorNome = trim($request->separador_nome);
    $distribuicaoAberta = DemandaDistribuicao::query()
        ->where('demanda_id', $demanda->id)
        ->where('separador_nome', $separadorNome)
        ->whereNull('finalizado_em')
        ->first();

    if ($distribuicaoAberta) {
        $distribuicaoAberta->increment('quantidade_pecas', $qtd);
    } else {
        DemandaDistribuicao::create([
            'demanda_id' => $demanda->id,
            'separador_nome' => $separadorNome,
            'quantidade_pecas' => $qtd,
        ]);
    }

    if (!$demanda->separacao_iniciada_em) {
        $demanda->update([
            'separacao_iniciada_em' => now(),
            'status' => 'SEPARANDO',
        ]);
    }

    return back()->with('success', "Distribuição registrada na DT {$demanda->fo}.");
}

public function finalizarSeparador(Request $request, $id)
{
    $request->validate([
        'separador_nome' => 'required|string|max:150',
        'resultado' => 'required|in:PARCIAL,COMPLETA',
    ]);

    $demanda = Demanda::findOrFail($id);
    $separadorNome = trim($request->separador_nome);

    $distribuicao = DemandaDistribuicao::query()
        ->where('demanda_id', $demanda->id)
        ->where('separador_nome', $separadorNome)
        ->whereNull('finalizado_em')
        ->first();

    if (! $distribuicao) {
        return back()->with('error', "Não existe distribuição em aberto para o separador {$separadorNome} na DT {$demanda->fo}.");
    }

    $distribuicao->update([
        'finalizado_em' => now(),
        'resultado' => $request->resultado,
    ]);

    $totalPicking = (int) round((float) $demanda->itens()->where('sobra', '>', 0)->sum('sobra'));
    $totalDistribuido = (int) $demanda->distribuicoes()->sum('quantidade_pecas');
    $abertas = (int) $demanda->distribuicoes()->whereNull('finalizado_em')->count();

    if ($totalPicking > 0 && $totalDistribuido >= $totalPicking && $abertas === 0) {
        $temParcial = $demanda->distribuicoes()->where('resultado', 'PARCIAL')->exists();
        $demanda->update([
            'separacao_finalizada_em' => now(),
            'separacao_resultado' => $temParcial ? 'PARCIAL' : 'COMPLETA',
            'status' => $temParcial ? 'A_CONFERIR' : 'CONFERIDO',
        ]);
    }

    return back()->with('success', "Separador {$separadorNome} finalizado na DT {$demanda->fo} ({$request->resultado}).");
}

public function iniciarSeparacao($id)
{
    request()->validate([
        'separador_ids' => 'nullable|array',
        'separador_ids.*' => 'integer|exists:_tb_usuarios,id_user',
    ]);

    $demanda = Demanda::findOrFail($id);
    if (! $demanda->possui_sobra) {
        return back()->with('error', "A DT {$demanda->fo} não possui sobra para separação.");
    }
    if ($demanda->separacao_iniciada_em && ! $demanda->separacao_finalizada_em) {
        return back()->with('error', "A DT {$demanda->fo} já está em separação.");
    }

    $separadorIds = array_values(array_unique(array_map('intval', (array) request('separador_ids', []))));
    $demanda->update([
        'separador_id' => $separadorIds[0] ?? $demanda->separador_id,
        'separacao_iniciada_em' => now(),
        'separacao_finalizada_em' => null,
        'separacao_resultado' => null,
        'status' => 'SEPARANDO',
    ]);
    if (!empty($separadorIds)) {
        $demanda->separadores()->sync($separadorIds);
    }

    return back()->with('success', "Separação da DT {$demanda->fo} iniciada.");
}

public function finalizarSeparacao(Request $request, $id)
{
    $request->validate([
        'resultado' => 'required|in:PARCIAL,COMPLETA',
    ]);

    $demanda = Demanda::findOrFail($id);
    if (! $demanda->separacao_iniciada_em) {
        $totalDistribuido = (int) $demanda->distribuicoes()->sum('quantidade_pecas');
        if ($totalDistribuido > 0) {
            $primeiraDistribuicao = $demanda->distribuicoes()->orderBy('created_at')->first();
            $demanda->separacao_iniciada_em = $primeiraDistribuicao?->created_at ?? now();
        } else {
            return back()->with('error', "A DT {$demanda->fo} ainda não foi iniciada.");
        }
    }
    if ($demanda->separacao_finalizada_em) {
        return back()->with('error', "A DT {$demanda->fo} já foi finalizada.");
    }
    $demanda->separacao_finalizada_em = now();
    $demanda->separacao_resultado = $request->resultado;
    $demanda->status = $request->resultado === 'COMPLETA' ? 'CONFERIDO' : 'A_CONFERIR';
    $demanda->save();

    return back()->with('success', "Separação da DT {$demanda->fo} finalizada como {$request->resultado}.");
}

public function dashboardOperacional()
{
    $turno = request('turno');
    $data = request('data');
    $base = Demanda::query()->where('possui_sobra', true);
    $base = $this->aplicarFiltrosOperacionais($base, $turno, $data);

    $status = [
        'pendente' => (clone $base)->whereNull('separacao_iniciada_em')->count(),
        'em_separacao' => (clone $base)->whereNotNull('separacao_iniciada_em')->whereNull('separacao_finalizada_em')->count(),
        'finalizado_parcial' => (clone $base)->where('separacao_resultado', 'PARCIAL')->count(),
        'finalizado_completo' => (clone $base)->where('separacao_resultado', 'COMPLETA')->count(),
    ];

    $tempoDiffExpr = $this->tempoDiffMinExpr('separacao_iniciada_em', 'separacao_finalizada_em');

    $tempoMedioMin = (clone $base)
        ->whereNotNull('separacao_iniciada_em')
        ->whereNotNull('separacao_finalizada_em')
        ->selectRaw("AVG({$tempoDiffExpr}) as media")
        ->value('media');

    $ranking = DB::table('_tb_demanda_distribuicoes as dd')
        ->join('_tb_demanda as d', 'd.id', '=', 'dd.demanda_id')
        ->where('d.possui_sobra', true)
        ->whereNotNull('dd.finalizado_em')
        ->whereNotNull('dd.separador_nome')
        ->whereRaw("TRIM(dd.separador_nome) <> ''")
        ->when($data, fn($q) => $q->whereDate('dd.created_at', $data))
        ->when($turno, function ($q) use ($turno) {
            $this->aplicarFiltroTurnoSql($q, 'dd.created_at', $turno);
        })
        ->groupBy('dd.separador_nome')
        ->selectRaw('dd.separador_nome as separador_nome')
        ->selectRaw('COUNT(*) as total_separacoes')
        ->selectRaw("AVG(".$this->tempoDiffMinExpr('dd.created_at', 'dd.finalizado_em').") as tempo_medio_min")
        ->orderByDesc('total_separacoes')
        ->limit(10)
        ->get();

    $comparativoTurno = collect(['MANHA', 'TARDE', 'NOITE'])->map(function ($nomeTurno) use ($data) {
        $q = Demanda::query()
            ->where('possui_sobra', true)
            ->whereNotNull('separacao_iniciada_em')
            ->whereNotNull('separacao_finalizada_em');
        $this->aplicarFiltroTurnoSql($q, 'separacao_iniciada_em', $nomeTurno);

        if ($data) {
            $q->whereDate('separacao_iniciada_em', $data);
        }

        $tempo = (clone $q)
            ->selectRaw("AVG(".$this->tempoDiffMinExpr('separacao_iniciada_em', 'separacao_finalizada_em').") as media")
            ->value('media');

        return [
            'turno' => $nomeTurno,
            'separacoes' => (clone $q)->count(),
            'tempo_medio' => $tempo ? round((float) $tempo, 1) : null,
        ];
    });

    $dateExpr = $this->dateExpr('separacao_finalizada_em');
    $evolucao7Dias = Demanda::query()
        ->where('possui_sobra', true)
        ->whereNotNull('separacao_finalizada_em')
        ->when($turno, function ($q) use ($turno) {
            $this->aplicarFiltroTurnoSql($q, 'separacao_iniciada_em', $turno);
        })
        ->whereDate('separacao_finalizada_em', '>=', now()->subDays(6)->toDateString())
        ->selectRaw("{$dateExpr} as dia")
        ->selectRaw('COUNT(*) as total')
        ->groupBy('dia')
        ->orderBy('dia')
        ->get();

    $mapEvolucao = $evolucao7Dias->pluck('total', 'dia');
    $labels7 = collect(range(6, 0))->map(fn($d) => now()->subDays($d)->format('Y-m-d'));
    $series7 = $labels7->map(fn($dia) => (int) ($mapEvolucao[$dia] ?? 0));

    $dadosGraficos = [
        'status' => [
            'labels' => ['A separar', 'Separando', 'Finalizado parcial', 'Finalizado completo'],
            'values' => [
                (int) $status['pendente'],
                (int) $status['em_separacao'],
                (int) $status['finalizado_parcial'],
                (int) $status['finalizado_completo'],
            ],
        ],
        'turnos' => [
            'labels' => $comparativoTurno->pluck('turno')->values(),
            'values' => $comparativoTurno->pluck('separacoes')->map(fn($v) => (int) $v)->values(),
        ],
        'ranking' => [
            'labels' => $ranking->pluck('separador_nome')->values(),
            'values' => $ranking->pluck('total_separacoes')->map(fn($v) => (int) $v)->values(),
        ],
        'evolucao7' => [
            'labels' => $labels7->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->values(),
            'values' => $series7->values(),
        ],
    ];

    return view('demandas.dashboard_operacional', [
        'status' => $status,
        'tempoMedioMin' => $tempoMedioMin ? round((float) $tempoMedioMin, 1) : null,
        'ranking' => $ranking,
        'comparativoTurno' => $comparativoTurno,
        'turnoSelecionado' => $turno,
        'dataSelecionada' => $data,
        'dadosGraficos' => $dadosGraficos,
    ]);
}

public function relatoriosOperacional()
{
    $base = Demanda::query()->where('possui_sobra', true);
    $total = (clone $base)->count();
    $parcial = (clone $base)->where('separacao_resultado', 'PARCIAL')->count();
    $completa = (clone $base)->where('separacao_resultado', 'COMPLETA')->count();
    $abertas = (clone $base)->whereNull('separacao_finalizada_em')->count();

    $tempoMedioMin = (clone $base)
        ->whereNotNull('separacao_iniciada_em')
        ->whereNotNull('separacao_finalizada_em')
        ->selectRaw("AVG(".$this->tempoDiffMinExpr('separacao_iniciada_em', 'separacao_finalizada_em').") as media")
        ->value('media');

    return view('demandas.relatorios', [
        'total' => $total,
        'parcial' => $parcial,
        'completa' => $completa,
        'abertas' => $abertas,
        'tempoMedioMin' => $tempoMedioMin ? round((float) $tempoMedioMin, 1) : null,
    ]);
}

private function aplicarFiltrosOperacionais($query, ?string $turno, ?string $data)
{
    if ($data) {
        $query->whereDate('separacao_iniciada_em', $data);
    }

    if ($turno) {
        $this->aplicarFiltroTurnoSql($query, 'separacao_iniciada_em', $turno);
    }

    return $query;
}

private function aplicarFiltroTurnoSql($query, string $colunaDataHora, ?string $turno): void
{
    if ($turno === 'MANHA') {
        $query->whereRaw("TIME({$colunaDataHora}) BETWEEN ? AND ?", ['06:00:00', '13:59:59']);
        return;
    }

    if ($turno === 'TARDE') {
        $query->whereRaw("TIME({$colunaDataHora}) BETWEEN ? AND ?", ['14:00:00', '21:59:59']);
        return;
    }

    if ($turno === 'NOITE') {
        $query->where(function ($q) use ($colunaDataHora) {
            $q->whereRaw("TIME({$colunaDataHora}) BETWEEN ? AND ?", ['22:00:00', '23:59:59'])
              ->orWhereRaw("TIME({$colunaDataHora}) BETWEEN ? AND ?", ['00:00:00', '05:59:59']);
        });
    }
}

private function tempoDiffMinExpr(string $colInicio, string $colFim): string
{
    $driver = DB::connection()->getDriverName();
    if ($driver === 'sqlite') {
        return "(julianday({$colFim}) - julianday({$colInicio})) * 1440";
    }

    return "TIMESTAMPDIFF(MINUTE, {$colInicio}, {$colFim})";
}

private function dateExpr(string $column): string
{
    $driver = DB::connection()->getDriverName();
    return $driver === 'sqlite' ? "date({$column})" : "DATE({$column})";
}

}
