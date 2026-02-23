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
                <h3 class="mb-1 fw-bold text-dark">Demandas Lançadas</h3>
                <p class="text-muted mb-0 small">Gerencie recebimentos e expedições</p>
            </div>
        </div>
        <div class="d-flex gap-2">
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

    <!-- Card de Filtros (padrão gestão de estoque) -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('demandas.index') }}" class="row g-3">
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">FO</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-pound text-muted"></i>
                        </span>
                        <input type="text" name="fo" class="form-control border-start-0" placeholder="Digite o FO" value="{{ request('fo') }}">
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
                    <label class="form-label small text-muted mb-1">Tipo</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="mdi mdi-compare-horizontal text-muted"></i>
                        </span>
                        <select name="tipo" class="form-select">
                            <option value="">Todos</option>
                            <option value="RECEBIMENTO" {{ request('tipo')=='RECEBIMENTO' ? 'selected' : '' }}>Recebimento</option>
                            <option value="EXPEDICAO" {{ request('tipo')=='EXPEDICAO' ? 'selected' : '' }}>Expedição</option>
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
                    @if(request()->hasAny(['fo','transportadora','tipo','data_inicio','data_fim']))
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
                                    <i class="mdi mdi-pound me-1"></i> FO
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold">
                                    <i class="mdi mdi-truck-outline me-1"></i> Transportadora
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">
                                    <i class="mdi mdi-dock-left me-1"></i> Doca
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">
                                    <i class="mdi mdi-compare-horizontal me-1"></i> Tipo
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-end">
                                    <i class="mdi mdi-counter me-1"></i> Qtd
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-end">
                                    <i class="mdi mdi-weight-kilogram me-1"></i> Peso (kg)
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-end">
                                    <i class="mdi mdi-cash me-1"></i> Valor
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">
                                    <i class="mdi mdi-calendar-clock me-1"></i> Agendamento
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">
                                    <i class="mdi mdi-login me-1"></i> Entrada
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">
                                    <i class="mdi mdi-logout me-1"></i> Saída
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold">
                                    <i class="mdi mdi-flag-outline me-1"></i> Status
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">
                                    <i class="mdi mdi-calendar me-1"></i> Criado em
                                </th>
                                <th class="px-4 py-3 text-muted small fw-semibold text-center">Ações</th>
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
                                    <td class="px-4 py-3">
                                        <input type="checkbox" name="ids[]" value="{{ $d->id }}" class="form-check-input">
                                    </td>
                                    <td class="px-4 py-3 fw-semibold">{{ $d->fo }}</td>
                                    <td class="px-4 py-3">{{ $d->transportadora }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-light text-dark border">{{ $d->doca }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-{{ $d->tipo === 'RECEBIMENTO' ? 'info' : 'success' }}">
                                            {{ $d->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end">{{ $d->quantidade ?? '-' }}</td>
                                    <td class="px-4 py-3 text-end">{{ $d->peso ? number_format($d->peso, 2, ',', '.') : '-' }}</td>
                                    <td class="px-4 py-3 text-end text-success fw-semibold">
                                        {{ $d->valor_carga ? 'R$ '.number_format($d->valor_carga, 2, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center small">{{ $d->hora_agendada }}</td>
                                    <td class="px-4 py-3 text-center small">{{ $d->entrada }}</td>
                                    <td class="px-4 py-3 text-center small">{{ $d->saida }}</td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-{{ $cores[$d->status] ?? 'secondary' }}">
                                                {{ str_replace('_', ' ', $d->status) }}
                                            </span>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" title="Alterar status">
                                                    <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @foreach($cores as $status => $cor)
                                                        <li>
                                                            <form action="{{ route('demandas.updateStatus', $d->id) }}" method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="{{ $status }}">
                                                                <button type="submit" class="dropdown-item small">
                                                                    <span class="badge bg-{{ $cor }} me-2"></span>
                                                                    {{ str_replace('_', ' ', $status) }}
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center small text-muted">
                                        {{ $d->created_at->timezone('America/Sao_Paulo')->format('d/m/y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('demandas.edit', $d->id) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Editar">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('demandas.destroy', $d->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Excluir"
                                                    onclick="return confirm('Deseja excluir esta demanda?')">
                                                    <i class="mdi mdi-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center py-5">
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