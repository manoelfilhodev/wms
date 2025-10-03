@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="page-heading mb-4">
        <h3 class="text-dark fw-bold">Painel de Controle</h3>
    </div>

    {{-- ================== NOTIFICAÇÕES ================== --}}
    @if(!empty($notificacoes))
        <div class="row mb-3">
            <div class="col-12">
                @foreach($notificacoes as $notificacao)
                    <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
                        <div>
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>{{ $notificacao->tipo ?? 'Aviso' }}:</strong>
                            {{ $notificacao->mensagem }}
                        </div>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($notificacao->created_at)->diffForHumans() }}
                        </small>
                    </div>
                    <form action="{{ route('notificacoes.ler', $notificacao->id) }}" method="POST" class="ms-3">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-sm btn-outline-secondary" title="Marcar como lida">
                            <i class="bi bi-x-lg"></i> Marcar como Lida
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ================== DEMANDAS DO DIA ================== --}}
    <h5 class="mt-5 mb-3 text-uppercase text-muted fw-semibold">Demandas do Dia</h5>
    <div class="d-flex justify-content-between mb-0">
        <button class="btn btn-success" onclick="gerarImagemERedirecionar2()">
            <i class="fab fa-whatsapp me-1"></i> Enviar Report Expedição - WhatsApp
        </button>
        <a href="{{ route('expedicao.relatorio.pdf') }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Gerar Relatório PDF
        </a>
    </div>
    <div id="divRelatorio2">
    <div class="row g-4">
        <div class="col-xl-3 col-sm-6">
            <x-dashboard.card title="Qtd Total Veículos" icon="bi-file-earmark-text"
                value="{{ $demandasHoje['resumo']['total'] ?? 0 }}" color="dark" />
        </div>
        <div class="col-xl-3 col-sm-6">
            <x-dashboard.card title="Qtd Veículos em Processo" icon="bi-truck"
                value="{{ $demandasHoje['resumo']['veiculos'] ?? 0 }}" color="primary" />
        </div>
        <div class="col-xl-3 col-sm-6">
            <x-dashboard.card title="Peças" icon="bi-box-seam"
                value="{{ $demandasHoje['resumo']['pecas'] ?? 0 }}" color="success" />
        </div>
        <div class="col-xl-3 col-sm-6">
            <x-dashboard.card title="Peso Total (kg)" icon="bi-weight"
                value="{{ number_format($demandasHoje['resumo']['peso'] ?? 0, 1, ',', '.') }}" color="warning" />
        </div>
    </div>

    <div class="row mt-4">
        {{-- Demandas por Status --}}
        <div class="col-xl-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom">Demandas por Status</div>
                <div class="card-body">
                    <div id="grafico-demandas-status"></div>
                </div>
            </div>
        </div>

        {{-- Veículos por Transportadora --}}
        <div class="col-xl-6 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom">Veículos por Transportadora</div>
                <div class="card-body">
                    <div id="grafico-transportadoras"></div>
                </div>
            </div>
        </div>
    </div>
    </div>

    {{-- ================== PRODUÇÃO DE KITS ================== --}}
    <div class="d-flex justify-content-between mb-0">
        <button class="btn btn-success" onclick="gerarImagemERedirecionar()">
            <i class="fab fa-whatsapp me-1"></i> Enviar Report Kits - WhatsApp
        </button>
        <a href="{{ route('relatorios.producao') }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf"></i> Gerar Relatório PDF
        </a>
    </div>

    <div class="row mt-1" id="divRelatorio">
        {{-- Tabela de Produção --}}
        <div class="col-lg-12 mb-1">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom">Produção de Kits (Hoje)</div>
                <div class="card-body">
                    <table class="table table-sm table-bordered text-center mb-0">
                        <thead>
                            <tr>
                                <th>Kit</th>
                                <th>Programado</th>
                                <th>Produzido</th>
                                <th class="d-none d-md-table-cell">Execução (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalProgramado = 0; $totalProduzido = 0; @endphp
                            @foreach($kitsHoje as $kit => $info)
                                @if($kit !== 'TOTAL')
                                    <tr>
                                        <td>{{ $kit }}</td>
                                        <td>{{ $info['programado'] }}</td>
                                        <td>{{ $info['produzido'] }}</td>
                                        <td class="d-none d-md-table-cell">
                                            {{ $info['programado'] > 0 ? round(($info['produzido'] / $info['programado']) * 100) : 0 }}%
                                        </td>
                                    </tr>
                                    @php
                                        $totalProgramado += $info['programado'];
                                        $totalProduzido += $info['produzido'];
                                    @endphp
                                @endif
                            @endforeach
                            <tr class="fw-bold bg-light">
                                <td>TOTAL</td>
                                <td>{{ $totalProgramado }}</td>
                                <td>{{ $totalProduzido }}</td>
                                <td class="d-none d-md-table-cell">
                                    {{ $totalProgramado > 0 ? round(($totalProduzido / $totalProgramado) * 100) : 0 }}%
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Gráfico de Kits --}}
        <div class="col-xl-12 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    @include('components.graficos.kits', ['kitsHoje' => $kitsHoje])
                </div>
            </div>
        </div>

        {{-- Produtividade por Hora --}}
        <div class="col-xl-12 col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom">Produtividade por Hora (Hoje)</div>
                <div class="card-body">
                    <div id="grafico-produtividade-hora"></div>
                </div>
            </div>
        </div>

        {{-- Projeção e Velocidade --}}
        <div class="row mt-4">
            <div class="col-xl-9 col-12 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold border-bottom">Projeção de Produtividade</div>
                    <div class="card-body">
                        <canvas id="graficoProdutividade" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-12 mb-4">
                <div class="card shadow-sm h-100 text-center">
                    <div class="card-header bg-white fw-semibold border-bottom">Velocidade de Produção</div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <canvas id="gaugeVelocidade" width="180" height="180"></canvas>
                        <div id="gaugeValor" class="fw-bold fs-5 mt-3"></div>
                        <small id="gaugeMeta" class="text-muted"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================== RESUMO DO DIA ================== --}}
    <div class="row justify-content-center mt-4">
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom">Resumo do Dia</div>
                <div class="card-body">
                    <table class="table table-sm table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Setor</th>
                                <th class="text-end">Quantidade (peças)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumoDia as $setor => $qtd)
                                <tr>
                                    <td class="text-uppercase">{{ str_replace('_', ' ', $setor) }}</td>
                                    <td class="text-end fw-semibold">{{ $qtd }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- ================== SCRIPTS ================== --}}
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        // Função genérica de gráfico de barras
        function renderBarChart(id, label, nomes, quantidades, color) {
            const options = {
                chart: { type: 'bar', height: 250 },
                series: [{ name: label, data: quantidades }],
                xaxis: { categories: nomes },
                colors: [color],
                plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 4 } }
            };
            new ApexCharts(document.querySelector(id), options).render();
        }

        // Função genérica de gráfico de linhas
        function renderGrafico(id, label, data, dias, color) {
            const options = {
                chart: { type: 'line', height: 250 },
                series: [{ name: label, data: data }],
                xaxis: { categories: dias, title: { text: 'Dia do Mês' } },
                yaxis: { title: { text: 'Qtd.' } },
                stroke: { curve: 'smooth' },
                colors: [color],
                markers: { size: 4 }
            };
            new ApexCharts(document.querySelector(id), options).render();
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Ranking
            renderBarChart('#grafico-ranking-armazenagem', 'Armazenagem',
                @json(collect($rankingOperadores['armazenagem'])->pluck('nome')),
                @json(collect($rankingOperadores['armazenagem'])->pluck('total')), '#3b82f6');

            renderBarChart('#grafico-ranking-separacao', 'Separação',
                @json(collect($rankingOperadores['separacao'])->pluck('nome')),
                @json(collect($rankingOperadores['separacao'])->pluck('total')), '#10b981');

            // Gráficos mensais
            renderGrafico('#grafico-armazenagem', 'Armazenagem', @json($dadosMensais['armazenagem']),
                @json($dadosMensais['dias']), '#3b82f6');
            renderGrafico('#grafico-separacao', 'Separação', @json($dadosMensais['separacao']),
                @json($dadosMensais['dias']), '#10b981');
            renderGrafico('#grafico-paletes', 'Paletes', @json($dadosMensais['paletes']),
                @json($dadosMensais['dias']), '#f97316');

            // Demandas
            new ApexCharts(document.querySelector("#grafico-demandas-status"), {
                chart: { type: 'donut', height: 300 },
                series: @json(collect($demandasHoje['por_status'])->pluck('total')),
                labels: @json(collect($demandasHoje['por_status'])->pluck('status')),
                colors: ['#0d6efd','#ffc107','#198754','#dc3545','#6610f2','#20c997'],
                legend: { position: 'bottom' }
            }).render();

            new ApexCharts(document.querySelector("#grafico-transportadoras"), {
                chart: { type: 'bar', height: 300 },
                series: [{ name: 'Veículos', data: @json(collect($demandasHoje['por_transportadora'])->pluck('total')) }],
                xaxis: { categories: @json(collect($demandasHoje['por_transportadora'])->pluck('transportadora')) },
                colors: ['#3b82f6']
            }).render();

            // Produtividade por Hora
            const horas = @json(collect($produtividadeHora)->pluck('hora'));
            const produzidos = @json(collect($produtividadeHora)->pluck('produzido'));
            new ApexCharts(document.querySelector("#grafico-produtividade-hora"), {
                chart: { type: 'line', height: 300 },
                series: [
                    { name: 'Produzido', type: 'column', data: produzidos },
                    { name: 'Meta', type: 'line', data: new Array(horas.length).fill(94) }
                ],
                xaxis: { categories: horas, title: { text: 'Hora do Dia' } },
                yaxis: { title: { text: 'Qtd Produzida' }, min: 0 },
                colors: ['#0d6efd', '#dc3545'],
                stroke: { curve: 'smooth', width: [0, 3] },
                plotOptions: { bar: { columnWidth: '40%', borderRadius: 4 } },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) { return opts.seriesIndex === 0 ? val : ''; },
                    style: { colors: ['#111'] },
                    offsetY: -10
                }
            }).render();
        });

        // Exportar imagem e abrir WhatsApp
        function gerarImagemERedirecionar() {
            const div = document.getElementById("divRelatorio");
            html2canvas(div).then(canvas => {
                const agora = new Date();
                const dataHora = agora.toLocaleString('sv-SE').replace(' ', '_').replace(':', '-');
                const nomeArquivo = `report-kit_${dataHora}.png`;

                const link = document.createElement('a');
                link.href = canvas.toDataURL("image/png");
                link.download = nomeArquivo;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                setTimeout(() => {
                    window.open('https://chat.whatsapp.com/FQl5hULSOqy7RCXL5PenLd', '_blank');
                }, 1000);
            });
        }
        
        function gerarImagemERedirecionar2() {
            const div = document.getElementById("divRelatorio2");
            html2canvas(div).then(canvas => {
                const agora = new Date();
                const dataHora = agora.toLocaleString('sv-SE').replace(' ', '_').replace(':', '-');
                const nomeArquivo = `report-expedicao_${dataHora}.png`;

                const link = document.createElement('a');
                link.href = canvas.toDataURL("image/png");
                link.download = nomeArquivo;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                setTimeout(() => {
                    window.open('https://chat.whatsapp.com/JpF5jqoVoBZF678kPHJz2o', '_blank');
                }, 1000);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let ctx = document.getElementById('graficoProdutividade').getContext('2d');

    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Produção Real',
                    data: [],
                    borderColor: 'blue',
                    borderWidth: 2,
                    tension: 0.2,
                    fill: false
                },
                {
                    label: 'Curva Ideal',
                    data: [],
                    borderColor: 'green',
                    borderDash: [5, 5],
                    borderWidth: 2,
                    tension: 0.2,
                    fill: false
                },
                {
                    label: 'Projeção Corrigida',
                    data: [],
                    borderColor: 'orange',
                    borderDash: [10, 5],
                    borderWidth: 2,
                    tension: 0.2,
                    fill: false
                },
                {
                    label: 'Meta',
                    data: [],
                    borderColor: 'red',
                    borderDash: [2, 2],
                    borderWidth: 2,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + ctx.formattedValue;
                        }
                    }
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    async function atualizarGrafico() {
        try {
            const resp = await fetch("{{ url('/api/dashboard/projecao-produtividade') }}");
            const data = await resp.json();

            // Labels (horas)
            chart.data.labels = data.curvaIdeal.map(c => c.hora);

            // Produção real
            chart.data.datasets[0].data = data.apontamentos.map(a => a.acumulado);

            // Curva ideal
            chart.data.datasets[1].data = data.curvaIdeal.map(c => c.valor);

            // Projeção corrigida
            chart.data.datasets[2].data = data.projecaoCorrigida.map(p => p.valor);

            // Meta (linha horizontal)
            chart.data.datasets[3].data = chart.data.labels.map(() => data.meta);

            chart.update();
        } catch (err) {
            console.error("Erro ao atualizar gráfico:", err);
        }
    }

    // Atualiza a cada 5 minutos
    setInterval(atualizarGrafico, 300000);
    atualizarGrafico();
});
</script>

<script>
document.addEventListener("DOMContentLoaded", async function () {
    const ctx = document.getElementById('gaugeVelocidade').getContext('2d');

    try {
        const resp = await fetch("{{ url('/api/dashboard/projecao-produtividade') }}");
        const data = await resp.json();

        const velAtual = data.velocidadeAtual || 0;
        const velNecessaria = data.velocidadeNecessaria || 0;
        const status = data.statusProdutividade; // "ok", "atencao" ou "baixo"

        // Define cor com base no status
        let cor;
        switch (status) {
            case 'ok': cor = '#28a745'; break;     // verde
            case 'atencao': cor = '#ffc107'; break; // amarelo
            default: cor = '#dc3545';              // vermelho
        }

        // Percentual de progresso
        let progresso = 0;
        if (velNecessaria > 0) {
            progresso = Math.min(velAtual / velNecessaria, 1);
        } else if (data.produzido >= data.meta) {
            progresso = 1; // já atingiu a meta
        }

        // Renderiza gauge
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [progresso * 100, 100 - (progresso * 100)],
                    backgroundColor: [cor, '#e0e0e0'],
                    borderWidth: 0
                }]
            },
            options: {
                rotation: -90,      // inicia em cima
                circumference: 180, // semi-círculo
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            }
        });

        // Texto abaixo do gauge
        if (velNecessaria === 0 && data.produzido >= data.meta) {
            document.getElementById('gaugeValor').innerText = "🎉 Meta atingida!";
            document.getElementById('gaugeMeta').innerText = `${data.produzido}/${data.meta} paletes`;
        } else {
            document.getElementById('gaugeValor').innerText = `📊 ${velAtual.toFixed(2)} paletes/h`;
            document.getElementById('gaugeMeta').innerText = `🎯 Meta: ${velNecessaria.toFixed(2)} paletes/h (${Math.round(progresso * 100)}%)`;
        }

    } catch (err) {
        console.error("Erro ao carregar gauge:", err);
    }
});
</script>


@endsection
