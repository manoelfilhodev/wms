<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ItemContagem;
use App\Models\ContagemItem;

class DashboardService
{
    
    public function getStatusContagemGeral()
{
    $hoje = \Carbon\Carbon::today();

    $totalMateriais = \App\Models\ItemContagem::count();

    $totalContados = \App\Models\ContagemItem::whereDate('data_contagem', $hoje)
        ->distinct('codigo_material')
        ->count('codigo_material');

    return $totalMateriais === $totalContados;
}
    public function getTotaisGerais(): array
    {
        return [
            'posicoes' => DB::table('_tb_posicoes')->count(),
            'produtos' => DB::table('_tb_materiais')->count(),
            'usuarios' => DB::table('_tb_usuarios')->count(),
        ];
    }

    public function getContagensDoDia(Carbon $data): array
    {
        return [
            'armazenagem' => DB::table('_tb_armazenagem')->whereDate('data_armazenagem', $data)->count(),
            'separacao'   => DB::table('_tb_separacao_itens')->whereDate('data_separacao', $data)->count(),
            'recebimento' => DB::table('_tb_recebimento')->whereDate('data_recebimento', $data)->count(),
            'expedicao'   => DB::table('_tb_expedicao')->whereDate('data_expedicao', $data)->count(),
            'paletes'     => DB::table('_tb_contagem_itens')->whereDate('updated_at', $data)->sum('quantidade'),
        ];
    }

    public function getVolumePorSetor(Carbon $data): array
    {
        return [
            'recebimento' => DB::table('_tb_recebimento')->whereDate('data_recebimento', $data)->count(),
            'armazenagem' => DB::table('_tb_armazenagem')->whereDate('data_armazenagem', $data)->count(),
            'separacao'   => DB::table('_tb_separacao_itens')->whereDate('data_separacao', $data)->count(),
            'expedicao'   => DB::table('_tb_expedicao')->whereDate('data_expedicao', $data)->count(),
        ];
    }

    public function getVolume7Dias(): array
    {
        $ultimos7Dias = [];

        for ($i = 6; $i >= 0; $i--) {
            $dia = Carbon::today()->subDays($i);
            $ultimos7Dias[] = [
                'data'  => $dia->format('Y-m-d'),
                'total' => DB::table('_tb_armazenagem')
                    ->whereDate('data_armazenagem', $dia)
                    ->count(),
            ];
        }

        return $ultimos7Dias;
    }

    public function getDadosMensaisPorDia(): array
    {
        $dias = [];
        $armazenagem = [];
        $separacao   = [];
        $paletes     = [];

        $inicioMes = Carbon::now()->startOfMonth();
        $hoje = Carbon::now();

        for ($data = $inicioMes->copy(); $data <= $hoje; $data->addDay()) {
            $label = $data->format('d');

            $dias[]        = $label;
            $armazenagem[] = DB::table('_tb_armazenagem')->whereDate('data_armazenagem', $data)->count();
            $separacao[]   = DB::table('_tb_separacao_itens')->whereDate('data_separacao', $data)->count();
            $paletes[]     = DB::table('_tb_contagem_itens')->whereDate('updated_at', $data)->sum('quantidade');
        }

        return [
            'dias'        => $dias,
            'armazenagem' => $armazenagem,
            'separacao'   => $separacao,
            'paletes'     => $paletes,
        ];
    }

    public function getNotificacoesPendentes(): array
    {
        return DB::table('_tb_notificacoes')
            ->where('status', 'pendente')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function marcarNotificacaoComoLida(int $id): void
    {
        DB::table('_tb_notificacoes')
            ->where('id', $id)
            ->update(['status' => 'visualizada']);
    }

    public function getOcupacaoRelativaPorPosicao(): array
    {
        $totalPosicoes       = DB::table('_tb_posicoes')->count();
        $capacidadePorPosicao = 15 * 25;
        $capacidadeTotal     = $totalPosicoes * $capacidadePorPosicao;

        $ocupacoes = DB::table('_tb_saldo_estoque as s')
            ->join('_tb_posicoes as p', 's.posicao_id', '=', 'p.id')
            ->select('p.codigo_posicao', DB::raw('SUM(s.quantidade) as total'))
            ->groupBy('p.codigo_posicao')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $resultado = [];

        foreach ($ocupacoes as $linha) {
            $percentual = ($capacidadeTotal > 0) ? ($linha->total / $capacidadeTotal) * 100 : 0;
            $resultado[] = [
                'endereco'   => $linha->codigo_posicao,
                'total'      => $linha->total,
                'percentual' => round($percentual, 2),
            ];
        }

        usort($resultado, fn($a, $b) => $b['percentual'] <=> $a['percentual']);

        return array_slice($resultado, 0, 6);
    }

    public function getOcupacaoTotalDoCD(): float
    {
        $totalPosicoes   = DB::table('_tb_posicoes')->count();
        $capacidadeTotal = $totalPosicoes * 15 * 25;
        $quantidadeAtual = DB::table('_tb_saldo_estoque')->sum('quantidade');

        return $capacidadeTotal > 0
            ? round(($quantidadeAtual / $capacidadeTotal) * 100, 2)
            : 0;
    }

    public function getRankingOperadores(): array
    {
        $hoje   = Carbon::today();
        $inicio = $hoje->copy()->subDays(6);

        // Armazenagem
        $armazenagem = DB::table('_tb_armazenagem as a')
            ->join('_tb_usuarios as u', 'a.usuario_id', '=', 'u.id_user')
            ->select(
                DB::raw("CONCAT(UCASE(SUBSTRING_INDEX(u.nome, ' ', 1)), ' ', UCASE(SUBSTRING_INDEX(u.nome, ' ', -1))) as nome"),
                DB::raw('SUM(a.quantidade) as total')
            )
            ->whereBetween('a.data_armazenagem', [$inicio, $hoje])
            ->groupBy('u.nome')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->toArray();

        // SeparaÃ§Ã£o
        $separacao = DB::table('_tb_separacao_itens as s')
            ->join('_tb_usuarios as u', 's.coletado_por', '=', 'u.id_user')
            ->select(
                DB::raw("CONCAT(UCASE(SUBSTRING_INDEX(u.nome, ' ', 1)), ' ', UCASE(SUBSTRING_INDEX(u.nome, ' ', -1))) as nome"),
                DB::raw('SUM(s.quantidade_separada) as total')
            )
            ->whereBetween('s.data_separacao', [$inicio, now()])
            ->groupBy('u.nome')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->toArray();

        return [
            'armazenagem' => $armazenagem,
            'separacao'   => $separacao,
        ];
    }

    public function getResumoDoDia(): array
    {
        $hoje   = Carbon::today();
        $resumo = [];

        $setores = [
            'armazenagem' => [
                'tabela'           => '_tb_armazenagem',
                'data_campo'       => 'data_armazenagem',
                'quantidade_campo' => 'quantidade',
            ],
            'separacao' => [
                'tabela'           => '_tb_separacao_itens',
                'data_campo'       => 'data_separacao',
                'quantidade_campo' => 'quantidade_separada',
            ],
            'kit_programado' => [
                'tabela'           => '_tb_kit_montagem',
                'data_campo'       => 'data_montagem',
                'quantidade_campo' => 'quantidade_programada',
            ],
            'kit_produzido' => [
                'tabela'           => '_tb_apontamentos_kits',
                'data_campo'       => 'updated_at',
                'quantidade_campo' => 'quantidade',
                'status'           => 'apontado',
            ],
        ];

        foreach ($setores as $nome => $conf) {
            $query = DB::table($conf['tabela'])
                ->whereDate($conf['data_campo'], $hoje);

            if (isset($conf['status'])) {
                $query->where('status', $conf['status']);
            }

            $quantidade = $query->sum($conf['quantidade_campo']);

            $resumo[$nome] = $quantidade;
        }

        return $resumo;
    }

    public function getProducaoDeKitsHoje(): array
    {
        $hoje = Carbon::today()->toDateString();

        $programados = DB::table('_tb_kit_montagem')
            ->select('codigo_material', DB::raw('SUM(quantidade_programada) as programado'))
            ->whereDate('data_montagem', $hoje)
            ->groupBy('codigo_material')
            ->pluck('programado', 'codigo_material')
            ->toArray();

        $produzidos = DB::table('_tb_apontamentos_kits')
            ->select('codigo_material', DB::raw('SUM(quantidade) as produzido'))
            ->where('status', 'apontado')
            ->whereDate('updated_at', $hoje)
            ->groupBy('codigo_material')
            ->pluck('produzido', 'codigo_material')
            ->toArray();

        $dados = [];
        foreach (array_keys($programados + $produzidos) as $codigo) {
            $dados[$codigo] = [
                'programado' => (int) ($programados[$codigo] ?? 0),
                'produzido'  => (int) ($produzidos[$codigo] ?? 0),
            ];
        }

        $totalProgramado = array_sum(array_column($dados, 'programado'));
        $totalProduzido  = array_sum(array_column($dados, 'produzido'));

        $dados['TOTAL'] = [
            'programado' => $totalProgramado,
            'produzido'  => $totalProduzido,
        ];

        return $dados;
    }

    public function getProducaoDeKitsMensal(): array
    {
        $inicioMes = Carbon::now()->startOfMonth();
        $fimHoje   = Carbon::now();
        $dados     = [];

        for ($data = $inicioMes->copy(); $data <= $fimHoje; $data->addDay()) {
            $dia = $data->format('d/m');

            $programado = DB::table('_tb_kit_montagem')
                ->whereDate('data_montagem', $data)
                ->sum('quantidade_programada');

            $produzido = DB::table('_tb_kit_montagem')
                ->whereDate('data_montagem', $data)
                ->sum('quantidade_produzida');

            $dados[] = [
                'dia'        => $dia,
                'programado' => $programado,
                'produzido'  => $produzido,
            ];
        }

        return $dados;
    }

    public function getAcuracidadeMensal()
    {
        return DB::table('_tb_inventario_itens')
            ->selectRaw('DATE(updated_at) as dia, 
                         SUM(CASE WHEN quantidade_fisica = quantidade_sistema THEN 1 ELSE 0 END) as corretos,
                         COUNT(*) as total')
            ->whereNotNull('updated_at')
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->map(function ($item) {
                return [
                    'dia'        => date('d', strtotime($item->dia)),
                    'acuracidade'=> $item->total > 0 ? round(($item->corretos / $item->total) * 100, 1) : 0,
                ];
            });
    }

    public function getResumoSkusHoje()
    {
        $hoje = now()->toDateString();

        return [
            'a_contar' => DB::table('_tb_inventario_itens')->whereDate('created_at', $hoje)->count(),
            'contados' => DB::table('_tb_inventario_itens')->whereDate('created_at', $hoje)->whereNotNull('quantidade_fisica')->count(),
        ];
    }

    public function getProgressoContagemHoje()
    {
        $resumo = $this->getResumoSkusHoje();

        return $resumo['a_contar'] > 0
            ? round(($resumo['contados'] / $resumo['a_contar']) * 100, 1)
            : 0;
    }

    public function getProdutividadeHoraHoje(): array
    {
        $hoje  = Carbon::today();
        $horas = range(6, 22);
        $dados = [];

        foreach ($horas as $h) {
            $inicio = $hoje->copy()->setTime($h, 0, 0);
            $fim    = $hoje->copy()->setTime($h, 59, 59);

            $produzido = DB::table('_tb_apontamentos_kits')
                ->where('status', 'apontado')
                ->whereBetween('updated_at', [$inicio, $fim])
                ->sum('quantidade');

            $dados[] = [
                'hora'      => str_pad($h, 2, '0', STR_PAD_LEFT) . 'h',
                'produzido' => (int) $produzido,
            ];
        }

        return $dados;
    }

    /** ðŸ”¹ NOVO MÃ‰TODO PARA DEMANDA DO DIA */
    public function getDemandasHoje(): array
    {
        $hoje = Carbon::today();

        $veiculos = DB::table('_tb_demanda')
            ->whereDate('created_at', $hoje)
            ->distinct('doca')
            ->count('doca');

        $total = DB::table('_tb_demanda')
            ->whereDate('created_at', $hoje)
            ->count();

        $pecas = DB::table('_tb_demanda')
            ->whereDate('created_at', $hoje)
            ->sum('quantidade');

        $peso = DB::table('_tb_demanda')
            ->whereDate('created_at', $hoje)
            ->sum('peso');

        $porStatus = DB::table('_tb_demanda')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', $hoje)
            ->groupBy('status')
            ->get();

        $porTransportadora = DB::table('_tb_demanda')
            ->select('transportadora', DB::raw('COUNT(*) as total'))
            ->whereDate('created_at', $hoje)
            ->groupBy('transportadora')
            ->get();

        return [
            'resumo'            => [
                'veiculos' => $veiculos,
                'total'    => $total,
                'pecas'    => $pecas,
                'peso'     => $peso,
            ],
            'por_status'        => $porStatus,
            'por_transportadora'=> $porTransportadora,
        ];
    }
    
public function getProjecaoProdutividade()
{
    $hoje = Carbon::today();

    // 🔹 Meta = total de etiquetas geradas (GERADO + APONTADO)
    $meta = DB::table('_tb_apontamentos_kits')
        ->whereDate('data', $hoje)
        ->whereIn('status', ['GERADO', 'APONTADO'])
        ->count();

    // 🔹 Turno (06h às 22h) com 2h de pausa
    $horaInicio = Carbon::today()->setTime(6, 0, 0);
    $horaFim = Carbon::today()->setTime(22, 0, 0);
    $horasUteis = ($horaFim->diffInMinutes($horaInicio) - 120) / 60;

    $intervalo = $horaInicio->copy();
    $curvaIdeal = [];
    $acumulado = [];

    while ($intervalo <= $horaFim) {
        // Percentual do tempo decorrido em relação ao turno
        $percentual = $horaInicio->diffInMinutes($intervalo) / $horaInicio->diffInMinutes($horaFim);

        // Curva ideal
        $curvaIdeal[] = [
            'hora' => $intervalo->format('H:i'),
            'valor' => round($meta * $percentual)
        ];

        // Produção real acumulada (apenas APONTADO)
        $count = DB::table('_tb_apontamentos_kits')
            ->whereDate('data', $hoje)
            ->whereTime('updated_at', '<=', $intervalo->format('H:i:s'))
            ->where('status', 'APONTADO')
            ->count();

        $acumulado[] = [
            'hora' => $intervalo->format('H:i'),
            'acumulado' => $count,
        ];

        $intervalo->addMinutes(30);
    }

    // 🔹 Produzido até agora
    $produzido = end($acumulado)['acumulado'] ?? 0;

    // 🔹 Velocidade atual e necessária
    $agora = Carbon::now();
    $tempoDecorrido = max(1, $horaInicio->diffInMinutes($agora) / 60); // em horas
    $velocidadeAtual = $produzido > 0 ? round($produzido / $tempoDecorrido, 2) : 0;

    // Se já atingiu a meta, velocidade necessária = 0
    if ($produzido >= $meta) {
        $velocidadeNecessaria = 0;
        $statusProdutividade = 'ok'; // já bateu a meta
    } else {
        $velocidadeNecessaria = $meta > 0 ? round($meta / $horasUteis, 2) : 0;

        // Classificação do status
        $progresso = $velocidadeNecessaria > 0 ? $velocidadeAtual / $velocidadeNecessaria : 0;
        if ($progresso >= 1) {
            $statusProdutividade = 'ok';      // verde
        } elseif ($progresso >= 0.8) {
            $statusProdutividade = 'atencao'; // amarelo
        } else {
            $statusProdutividade = 'baixo';   // vermelho
        }
    }

    // 🔹 Projeção corrigida
    $projecaoCorrigida = [];
    if ($produzido < $meta) {
        $ultimo = end($acumulado);
        $ultimoHorario = Carbon::today()->setTimeFromTimeString($ultimo['hora']);
        $tempoRestante = $ultimoHorario->diffInMinutes($horaFim);

        if ($tempoRestante > 0 && $tempoDecorrido > 0) {
            $ritmoMedio = $produzido / ($tempoDecorrido * 60); // paletes/minuto
            $intervalo = $ultimoHorario->copy();

            while ($intervalo <= $horaFim) {
                $tempoProjecao = $horaInicio->diffInMinutes($intervalo);
                $projecaoCorrigida[] = [
                    'hora' => $intervalo->format('H:i'),
                    'valor' => round($ritmoMedio * $tempoProjecao, 0)
                ];
                $intervalo->addMinutes(30);
            }
        }
    }

    return [
        'meta' => $meta,
        'produzido' => $produzido,
        'apontamentos' => $acumulado,
        'curvaIdeal' => $curvaIdeal,
        'projecaoCorrigida' => $projecaoCorrigida,
        'velocidadeNecessaria' => $velocidadeNecessaria,
        'velocidadeAtual' => $velocidadeAtual,
        'statusProdutividade' => $statusProdutividade,
    ];
}

// 🔹 Produção por operador
    public function getProducaoPorOperador(): array
    {
        $hoje = Carbon::today();
        return DB::table('_tb_apontamentos_kits as k')
            ->join('_tb_usuarios as u', 'k.user_id', '=', 'u.id_user')
            ->select('u.nome', DB::raw('SUM(k.quantidade) as total'))
            ->whereDate('k.updated_at', $hoje)
            ->where('k.status', 'apontado')
            ->groupBy('u.nome')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

// 🔹 Produção por SKU
    public function getProducaoPorSku(): array
    {
        $hoje = Carbon::today();
        return DB::table('_tb_apontamentos_kits')
            ->select('codigo_material', DB::raw('SUM(quantidade) as total'))
            ->whereDate('updated_at', $hoje)
            ->where('status', 'apontado')
            ->groupBy('codigo_material')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

public function getMetaRealizado(Carbon $data): array
{
    // Planejado (GERADO + APONTADO) agrupado por SKU
    $planejado = \DB::table('_tb_apontamentos_kits')
        ->select('codigo_material', \DB::raw('SUM(quantidade) as qtd'))
        ->whereDate('updated_at', $data)
        ->whereIn('status', ['GERADO', 'APONTADO'])
        ->groupBy('codigo_material')
        ->pluck('qtd', 'codigo_material')
        ->toArray();

    // Realizado (somente APONTADO) agrupado por SKU
    $realizado = \DB::table('_tb_apontamentos_kits')
        ->select('codigo_material', \DB::raw('SUM(quantidade) as qtd'))
        ->whereDate('updated_at', $data)
        ->where('status', 'APONTADO')
        ->groupBy('codigo_material')
        ->pluck('qtd', 'codigo_material')
        ->toArray();

    // Monta array consolidado por SKU
    $detalhes = [];
    foreach (array_keys($planejado + $realizado) as $sku) {
        $detalhes[] = [
            'sku'       => $sku,
            'planejado' => (int) ($planejado[$sku] ?? 0),
            'realizado' => (int) ($realizado[$sku] ?? 0),
        ];
    }

    return [
        'planejado' => array_sum($planejado),
        'realizado' => array_sum($realizado),
        'detalhes'  => $detalhes,
    ];
}


    /**
     * Tempo médio entre paletes (em minutos)
     */
public function getTempoMedioPaletes($data)
{
    $inicioMes = Carbon::parse($data)->startOfMonth();
    $fim = Carbon::parse($data);

    $resultados = [];
    $todasMedias = [];

    for ($dia = $inicioMes->copy(); $dia <= $fim; $dia->addDay()) {
        $apontamentos = \DB::table('_tb_apontamentos_kits')
            ->whereDate('updated_at', $dia)
            ->where('status', 'APONTADO')
            ->orderBy('updated_at')
            ->pluck('updated_at');

        $tempos = [];
        for ($i = 1; $i < count($apontamentos); $i++) {
            $anterior = Carbon::parse($apontamentos[$i - 1]);
            $atual = Carbon::parse($apontamentos[$i]);
            $tempos[] = $anterior->diffInMinutes($atual);
        }

        $media = count($tempos) > 0 ? round(array_sum($tempos) / count($tempos), 2) : 0;
        $todasMedias[] = $media;

        $resultados[] = [
            'data' => $dia->format('d/m'),
            'media' => $media,
        ];
    }

    // 🔹 Calcula média do mês
    $mediaMensal = count($todasMedias) > 0 ? round(array_sum($todasMedias) / count($todasMedias), 2) : 0;

    return [
        'dias' => $resultados,
        'media_mensal' => $mediaMensal
    ];
}



    /**
     * Produção acumulada na semana até a data
     */
    public function getProducaoAcumuladaSemana(Carbon $data): array
    {
        $inicioSemana = $data->copy()->startOfWeek();
        $fim          = $data->copy()->endOfDay();

        $dias = DB::table('_tb_apontamentos_kits')
            ->selectRaw('DATE(updated_at) as dia, SUM(quantidade) as total')
            ->where('status', 'APONTADO')
            ->whereBetween('updated_at', [$inicioSemana, $fim])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $acumulado = [];
        $soma = 0;
        foreach ($dias as $d) {
            $soma += $d->total;
            $acumulado[] = [
                'data' => Carbon::parse($d->dia)->format('d/m'),
                'total' => $soma,
            ];
        }

        return $acumulado;
    }

    /**
     * Produção diária (por data)
     */
public function getProducaoDiaria(Carbon $data): array
    {
        $inicioMes = $data->copy()->startOfMonth();
        $fim       = $data->copy()->endOfDay();

        $result = DB::table('_tb_apontamentos_kits')
            ->selectRaw('DATE(updated_at) as dia, SUM(quantidade) as total')
            ->where('status', 'APONTADO')
            ->whereBetween('updated_at', [$inicioMes, $fim])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        return $result->map(function ($item) {
            return [
                'data' => Carbon::parse($item->dia)->format('d/m'),
                'total' => (int) $item->total,
            ];
        })->toArray();
    }


    /**
     * Produção por hora no dia
     */
public function getProducaoPorHora(Carbon $data): array
{
    $horas = range(6, 22); // faixa de 06h às 22h
    $dados = [];

    foreach ($horas as $h) {
        $inicio = $data->copy()->setTime($h, 0, 0);
        $fim    = $data->copy()->setTime($h, 59, 59);

        $produzido = \DB::table('_tb_apontamentos_kits')
            ->where('status', 'APONTADO')
            ->whereBetween('updated_at', [$inicio, $fim])
            ->sum('quantidade'); // ✅ corrigido para somar a quantidade

        $dados[] = [
            'hora'  => str_pad($h, 2, '0', STR_PAD_LEFT) . 'h',
            'total' => (int) $produzido,
        ];
    }

    return $dados;
}


    /**
     * Produção por material (SKU)
     */
public function getProducaoPorMaterial(Carbon $data): array
    {
        $inicioMes = $data->copy()->startOfMonth();
        $fim       = $data->copy()->endOfDay();

        return DB::table('_tb_apontamentos_kits')
            ->select('codigo_material', DB::raw('SUM(quantidade) as total'))
            ->where('status', 'APONTADO')
            ->whereBetween('updated_at', [$inicioMes, $fim])
            ->groupBy('codigo_material')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    /**
     * Top 5 paletes produzidos
     */
    public function getTop5Paletes(Carbon $data): array
    {
        $ontem = $data->copy()->subDay();

        return DB::table('_tb_apontamentos_kits')
            ->select('palete_uid', DB::raw('SUM(quantidade) as quantidade'))
            ->where('status', 'APONTADO')
            ->whereDate('updated_at', $ontem)
            ->groupBy('palete_uid')
            ->orderByDesc('quantidade')
            ->limit(5)
            ->get()
            ->toArray();
    }



}
