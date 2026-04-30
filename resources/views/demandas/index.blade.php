@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('partials.breadcrumb-auto')
    
    <!-- Header com ícone roxo (padrão gestão de estoque) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-wrapper me-3">
                <i class="mdi mdi-truck-fast display-6"></i>
            </div>
            <div>
                <h3 class="mb-1 fw-bold text-dark">{{ !empty($modoOperacional) ? 'DTs Picking' : 'Demandas Lançadas' }}</h3>
                <p class="text-muted mb-0 small">{{ !empty($modoOperacional) ? 'Visão do ADM Operacional: apenas DTs com picking' : 'Gerencie recebimentos e expedições' }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('demandas.operacional') }}" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Ver somente DTs com sobra">
                <i class="mdi mdi-filter-variant"></i> Operacional
            </a>
            <a href="{{ route('demandas.dashboardOperacional') }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Dashboard de produtividade">
                <i class="mdi mdi-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('demandas.relatorios') }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Relatórios operacionais">
                <i class="mdi mdi-file-chart-outline"></i> Relatórios
            </a>
            <a href="{{ route('demandas.create') }}" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Lançar nova demanda">
                <i class="mdi mdi-plus me-1"></i> Nova
            </a>
            <a href="{{ route('demandas.import.view') }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Importar via Excel">
                <i class="mdi mdi-file-excel"></i>
            </a>
            <a href="{{ route('demandas.export', request()->query()) }}" class="btn btn-outline-secondary btn-sm" data-bs-toggle="tooltip" title="Exportar">
                <i class="mdi mdi-download"></i>
            </a>
            <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()" data-bs-toggle="tooltip" title="Atualizar">
                <i class="mdi mdi-refresh"></i>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle-outline me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card de Filtros (padrão gestão de estoque) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('demandas.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">DT</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-pound text-muted"></i>
                        </span>
                        <input type="text" name="fo" class="form-control border-start-0" placeholder="Digite a DT" value="{{ request('fo') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Transportadora</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-truck-outline text-muted"></i>
                        </span>
                        <input type="text" name="transportadora" class="form-control border-start-0" placeholder="Nome da transportadora" value="{{ request('transportadora') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="mdi mdi-compare-horizontal text-muted"></i>
                        </span>
                        <select name="status" class="form-select">
                            <option value="">Todos</option>
                            <option value="A_SEPARAR" {{ request('status')=='A_SEPARAR' ? 'selected' : '' }}>A separar</option>
                            <option value="SEPARANDO" {{ request('status')=='SEPARANDO' ? 'selected' : '' }}>Separando</option>
                            <option value="SEPARADO_PARCIAL" {{ request('status')=='SEPARADO_PARCIAL' ? 'selected' : '' }}>Separação parcial</option>
                            <option value="SEPARADO" {{ request('status')=='SEPARADO' ? 'selected' : '' }}>Separado</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Data Início</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-calendar-start text-muted"></i>
                        </span>
                        <input type="date" name="data_inicio" class="form-control border-start-0" value="{{ request('data_inicio') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Data Fim</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-calendar-end text-muted"></i>
                        </span>
                        <input type="date" name="data_fim" class="form-control border-start-0" value="{{ request('data_fim') }}">
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100" data-bs-toggle="tooltip" title="Aplicar filtros">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                    @if(request()->hasAny(['fo','transportadora','status','data_inicio','data_fim']))
                        <a href="{{ route('demandas.index') }}" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Limpar filtros">
                            <i class="mdi mdi-close"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Card da Tabela (padrão gestão de estoque) -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <form action="{{ route('demandas.updateMultiple') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3">
                                    <input type="checkbox" id="checkAll" class="form-check-input">
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold">
                                    <i class="mdi mdi-pound me-1"></i> DT
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold">
                                    <i class="mdi mdi-truck-outline me-1"></i> Transportadora
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">Itens c/ sobra</th>
                                <th class="px-4 py-3 text-muted small fw-semibold">
                                    <i class="mdi mdi-flag-outline me-1"></i> Status
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">Picking</th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">Distribuição</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $cores = [
                                    'GERAR'        => 'secondary',
                                    'A_SEPARAR'    => 'info',
                                    'SEPARANDO'    => 'primary',
                                    'A_CONFERIR'   => 'warning',
                                    'CONFERINDO'   => 'primary',
                                    'CONFERIDO'    => 'success',
                                    'A_CARREGAR'   => 'warning',
                                    'CARREGANDO'   => 'primary',
                                    'CARREGADO'    => 'success',
                                    'FATURANDO'    => 'danger',
                                    'LIBERADO'     => 'success',
                                ];
                            @endphp

                            @forelse($demandas as $d)
                                <tr class="border-bottom">
                                    @php
                                        $totalPicking = (int) round((float) ($d->total_pecas_picking ?? 0));
                                        $totalDistribuido = (int) ($d->total_pecas_distribuidas ?? 0);
                                        $restante = max(0, $totalPicking - $totalDistribuido);
                                        $percentual = $totalPicking > 0 ? min(100, (int) round(($totalDistribuido / $totalPicking) * 100)) : 0;

                                        $statusDinamico = 'A SEPARAR';
                                        $statusDinamicoCor = 'info';
                                        if ($d->separacao_finalizada_em) {
                                            if ($d->separacao_resultado === 'PARCIAL') {
                                                $statusDinamico = 'SEPARADO PARCIAL';
                                                $statusDinamicoCor = 'warning';
                                            } else {
                                                $statusDinamico = 'SEPARADO';
                                                $statusDinamicoCor = 'success';
                                            }
                                        } elseif ($totalDistribuido > 0 || $d->separacao_iniciada_em) {
                                            $statusDinamico = 'SEPARANDO';
                                            $statusDinamicoCor = 'primary';
                                        }

                                        $podeFinalizar = !$d->separacao_finalizada_em
                                            && $statusDinamico === 'SEPARANDO'
                                            && $totalPicking > 0
                                            && $totalDistribuido >= $totalPicking;
                                    @endphp
                                    <td class="px-4 py-3">
                                        <input type="checkbox" name="ids[]" value="{{ $d->id }}" class="form-check-input">
                                    </td>
                                    <td class="px-4 py-3 fw-semibold">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDistribuicao{{ $d->id }}">{{ $d->fo }}</a>
                                    </td>
                                    <td class="px-4 py-3">{{ $d->transportadora }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-light text-dark border">{{ $d->total_itens_com_sobra ?? 0 }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-{{ $statusDinamicoCor }}">
                                            {{ $statusDinamico }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="small text-muted mb-1">Peças a separar</div>
                                        <div class="fw-semibold">{{ $totalPicking }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center" style="min-width:220px;">
                                        <div class="small mb-1">{{ $totalDistribuido }}/{{ $totalPicking }} peças ({{ $percentual }}%)</div>
                                        <div class="progress" style="height:8px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentual }}%;"></div>
                                        </div>
                                        <div class="small text-muted mt-1">Restante: {{ $restante }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="mdi mdi-package-variant-closed display-4 d-block mb-3 opacity-25"></i>
                                            <p class="mb-0">Nenhuma demanda encontrada</p>
                                            <small>Tente ajustar os filtros ou criar uma nova demanda</small>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        @if($demandas->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Mostrando {{ $demandas->firstItem() }} a {{ $demandas->lastItem() }} de {{ $demandas->total() }} registros
                </small>
                {{ $demandas->links() }}
            </div>
        @endif
    </div>
</div>

@foreach($demandas as $d)
    @php
        $totalPicking = (int) round((float) ($d->total_pecas_picking ?? 0));
        $totalDistribuido = (int) ($d->total_pecas_distribuidas ?? 0);
        $restante = max(0, $totalPicking - $totalDistribuido);
    @endphp
    <div class="modal fade" id="modalDistribuicao{{ $d->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Distribuição da DT {{ $d->fo }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4"><div class="small text-muted">Total Picking</div><div class="fw-semibold">{{ $totalPicking }}</div></div>
                        <div class="col-md-4"><div class="small text-muted">Já Distribuído</div><div class="fw-semibold">{{ $totalDistribuido }}</div></div>
                        <div class="col-md-4"><div class="small text-muted">Saldo</div><div class="fw-semibold">{{ $restante }}</div></div>
                    </div>

                    @php
                        $inicio = $d->separacao_iniciada_em ? \Carbon\Carbon::parse($d->separacao_iniciada_em) : null;
                        $fim = $d->separacao_finalizada_em ? \Carbon\Carbon::parse($d->separacao_finalizada_em) : null;
                    @endphp
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="small text-muted">Início da separação</div>
                            <div class="fw-semibold">{{ $inicio ? $inicio->format('d/m/Y H:i:s') : '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Fim da separação</div>
                            <div class="fw-semibold">{{ $fim ? $fim->format('d/m/Y H:i:s') : '-' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Tempo da separação</div>
                            <div class="fw-semibold">
                                @if($inicio && $fim)
                                    {{ $inicio->diff($fim)->format('%H:%I:%S') }}
                                @elseif($inicio && !$fim)
                                    Em andamento
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('demandas.distribuir', $d->id) }}" class="row g-2 mb-3">
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1">Nome do separador</label>
                            <input type="text" name="separador_nome" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">Qtd peças</label>
                            <input type="number" name="quantidade_pecas" min="1" max="{{ $restante }}" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-sm btn-primary w-100">Distribuir</button>
                        </div>
                    </form>

                    @php
                        $distribuicoesPorSeparador = $d->distribuicoes
                            ->groupBy('separador_nome')
                            ->map(function ($itens) {
                                $inicio = $itens->min('created_at');
                                $fim = $itens->whereNotNull('finalizado_em')->max('finalizado_em');
                                $resultado = $itens->whereNotNull('resultado')->last()?->resultado;
                                return [
                                    'separador_nome' => $itens->first()->separador_nome,
                                    'quantidade_pecas' => (int) $itens->sum('quantidade_pecas'),
                                    'inicio' => $inicio ? \Carbon\Carbon::parse($inicio) : null,
                                    'fim' => $fim ? \Carbon\Carbon::parse($fim) : null,
                                    'resultado' => $resultado,
                                ];
                            })
                            ->values();
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Separador</th>
                                    <th class="text-end">Qtd peças</th>
                                    <th class="text-end">Início</th>
                                    <th class="text-end">Fim</th>
                                    <th class="text-end">Tempo</th>
                                    <th class="text-end">Status</th>
                                    <th class="text-end">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($distribuicoesPorSeparador as $dist)
                                    <tr>
                                        <td>{{ $dist['separador_nome'] }}</td>
                                        <td class="text-end">{{ $dist['quantidade_pecas'] }}</td>
                                        <td class="text-end">{{ $dist['inicio']?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td class="text-end">{{ $dist['fim']?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td class="text-end">
                                            @if($dist['inicio'] && $dist['fim'])
                                                {{ $dist['inicio']->diff($dist['fim'])->format('%H:%I:%S') }}
                                            @elseif($dist['inicio'])
                                                Em andamento
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($dist['fim'])
                                                @if($dist['resultado'] === 'PARCIAL')
                                                    <span class="badge bg-warning">Parcial</span>
                                                @else
                                                    <span class="badge bg-success">Completa</span>
                                                @endif
                                            @else
                                                <span class="badge bg-primary">Separando</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if(!$dist['fim'])
                                                <form action="{{ route('demandas.finalizarSeparador', $d->id) }}" method="POST" class="d-inline-flex gap-1">
                                                    @csrf
                                                    <input type="hidden" name="separador_nome" value="{{ $dist['separador_nome'] }}">
                                                    <button type="submit" name="resultado" value="PARCIAL" class="btn btn-sm btn-warning">Parcial</button>
                                                    <button type="submit" name="resultado" value="COMPLETA" class="btn btn-sm btn-success">Completa</button>
                                                </form>
                                            @else
                                                <span class="text-muted small">Finalizado</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">Sem distribuição registrada.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
document.getElementById('checkAll')?.addEventListener('change', function(){
    document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = this.checked);
});
</script>

<style>
    .icon-wrapper {
        width: 60px; height: 60px;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(102,126,234,0.3);
    }
    .icon-wrapper i { color: #fff !important; }

    .input-group-text { background-color: #f8f9fa; }
    .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.1); }
    .table tbody tr:hover { background-color: #f8f9fa; transition: background-color 0.2s ease; }
    .card { border-radius: 0.5rem; }
    .badge { font-weight: 500; padding: 0.35em 0.65em; }
</style>
@endsection
