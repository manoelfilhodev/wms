

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Demandas Lançadas</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Botões de ação -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('demandas.create') }}" class="btn btn-success">+ Nova Demanda</a>
        <a href="{{ route('demandas.import.view') }}" class="btn btn-outline-primary">
        Importar via Excel
    </a>
        <a href="{{ route('demandas.export', request()->query()) }}" class="btn btn-outline-success">
            📊 Exportar Excel
        </a>
    </div>
   

    <!-- Filtros -->
    <form method="GET" action="{{ route('demandas.index') }}" class="row g-2 mb-4">
    <div class="col-12 col-md-2">
        <input type="text" name="fo" class="form-control" placeholder="FO" value="{{ request('fo') }}">
    </div>
    <div class="col-12 col-md-3">
        <input type="text" name="transportadora" class="form-control" placeholder="Transportadora" value="{{ request('transportadora') }}">
    </div>
 
    <div class="col-12 col-md-2">
        <select name="tipo" class="form-select">
            <option value="">-- Tipo --</option>
            <option value="RECEBIMENTO" {{ request('tipo')=='RECEBIMENTO' ? 'selected' : '' }}>Recebimento</option>
            <option value="EXPEDICAO" {{ request('tipo')=='EXPEDICAO' ? 'selected' : '' }}>Expedição</option>
        </select>
    </div>
    <div class="col-6 col-md-2">
        <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
    </div>
    <div class="col-6 col-md-2">
        <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
    </div>
    <div class="col-6 col-md-1 d-flex">
        <button type="submit" class="btn btn-sm btn-outline-primary me-1" title="Filtrar">
            <i class="mdi mdi-filter"></i>
        </button>
        <a href="{{ route('demandas.index') }}" class="btn btn-sm btn-outline-secondary" title="Limpar">
            <i class="mdi mdi-broom"></i>
        </a>
    </div>
</form>


    <!-- Alteração em lote -->
    <form action="{{ route('demandas.updateMultiple') }}" method="POST">
        @csrf
        @method('PATCH')

        <!--<div class="d-flex mb-2">-->
        <!--    <select name="status" class="form-select w-auto me-2">-->
        <!--        <option value="">-- Mudar Status Selecionados --</option>-->
        <!--        <option value="GERAR">GERAR</option>-->
        <!--        <option value="A_SEPARAR">À SEPARAR</option>-->
        <!--        <option value="SEPARANDO">SEPARANDO</option>-->
        <!--        <option value="A_CONFERIR">À CONFERIR</option>-->
        <!--        <option value="CONFERINDO">CONFERINDO</option>-->
        <!--        <option value="CONFERIDO">CONFERIDO</option>-->
        <!--        <option value="A_CARREGAR">À CARREGAR</option>-->
        <!--        <option value="CARREGANDO">CARREGANDO</option>-->
        <!--        <option value="CARREGADO">CARREGADO</option>-->
        <!--        <option value="FATURANDO">FATURANDO</option>-->
        <!--        <option value="LIBERADO">LIBERADO</option>-->
        <!--    </select>-->
        <!--    <button type="submit" class="btn btn-primary">Aplicar em Lote</button>-->
        <!--</div>-->

        <!-- Tabela SEM scroll -->
        <table class="table table-striped table-hover align-middle">
            <thead class="table-primary">
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>FO</th>

                    <th>Transportadora</th>
                    <th>Doca</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Peso</th>
                    <th>Valor Carga</th>
                    <th>Agendamento</th>
                    <th>Entrada</th>
                    <th>Saída</th>
                    <th>Status</th>
                    <th>Criado em</th>
                    <th>Ações</th>
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

                @foreach($demandas as $d)
                <tr>
                    <td><input type="checkbox" name="ids[]" value="{{ $d->id }}"></td>
                    <td>{{ $d->fo }}</td>
            
                    <td>{{ $d->transportadora }}</td>
                    <td>{{ $d->doca }}</td>
                    <td>
                        <span class="badge bg-{{ $d->tipo === 'RECEBIMENTO' ? 'info' : 'success' }}">
                            {{ $d->tipo }}
                        </span>
                    </td>
                    <td>{{ $d->quantidade ?? '-' }}</td>
                    <td>{{ $d->peso ? number_format($d->peso, 2, ',', '.') : '-' }}</td>
                    <td>{{ $d->valor_carga ? 'R$ '.number_format($d->valor_carga, 2, ',', '.') : '-' }}</td>
                    <td>{{ $d->hora_agendada }}</td>
                    <td>{{ $d->entrada }}</td>
                    <td>{{ $d->saida }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-{{ $cores[$d->status] ?? 'secondary' }} me-2">
                                {{ $d->status }}
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="mdi mdi-swap-horizontal"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach($cores as $status => $cor)
                                        <li>
                                            <form action="{{ route('demandas.updateStatus', $d->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $status }}">
                                                <button type="submit" class="dropdown-item">
                                                    {{ $status }}
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </td>
                    <td>{{ $d->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('demandas.edit', $d->id) }}" class="btn btn-sm btn-outline-primary me-1">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <form action="{{ route('demandas.destroy', $d->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Deseja excluir esta demanda?')">
                                <i class="mdi mdi-trash-can"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>

<script>
document.getElementById('checkAll').addEventListener('change', function(){
    let checkboxes = document.querySelectorAll('input[name="ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
});
</script>
@endsection
