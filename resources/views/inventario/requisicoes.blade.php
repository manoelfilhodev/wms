@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Inventários Cíclicos</h4>
    <form method="GET" class="mb-3 d-flex gap-2">
    <div>
        <select name="status" class="form-select">
            <option value="">Todos os Status</option>
            <option value="aberta" {{ request('status') == 'aberta' ? 'selected' : '' }}>Aberta</option>
            <option value="contando" {{ request('status') == 'contando' ? 'selected' : '' }}>Contando</option>
            <option value="contado" {{ request('status') == 'contado' ? 'selected' : '' }}>Contado</option>
            <option value="concluida" {{ request('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
        </select>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>
</form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código</th>
                <th>Data</th>
                <th>Status</th>
                <th>Criador</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventarios as $inv)
                <tr>
                    <td>{{ $inv->id }}</td>
                    <td>{{ $inv->cod_requisicao }}</td>
                    <td>{{ \Carbon\Carbon::parse($inv->data_requisicao)->format('d/m/Y') }}</td>
                    <td>
    @if($inv->progresso == 100)
    <span class="badge bg-success"><i class="uil-check-circle"></i> Completo</span>
@elseif($inv->progresso >= 50)
    <span class="badge bg-info"><i class="uil-sync"></i> Em andamento</span>
@else
    <span class="badge bg-warning text-dark"><i class="uil-clock"></i> Iniciado</span>
@endif


    <div class="progress mt-1" style="height: 10px;">
        <div class="progress-bar 
            {{ $inv->progresso == 100 ? 'bg-success' : ($inv->progresso >= 50 ? 'bg-info' : 'bg-warning') }}"
            role="progressbar"
            style="width: {{ $inv->progresso }}%;"
            aria-valuenow="{{ $inv->progresso }}" aria-valuemin="0" aria-valuemax="100">
        </div>
    </div>
    <small title="{{ $inv->contados }} de {{ $inv->total_itens }} contados">
    {{ $inv->progresso }}%
</small>

</td>

                    <td>{{ $inv->usuario_criador }}</td>
                    <td>
                        @if($inv->status === 'contando')
                            <a href="{{ route('contar.inventario', ['idInventario' => $inv->id]) }}" class="btn btn-primary btn-sm">

    Iniciar Contagem
</a>
                        @elseif($inv->status === 'concluida')
                            <a href="{{ route('inventario.validacao', $inv->id) }}" class="btn btn-success btn-sm">Ver Validação</a>
                        @else
                            <span class="text-muted">Aguardando</span>
                        @endif
                        

    <a href="{{ route('inventario.resumo', $inv->id) }}" class="btn btn-outline-info btn-sm" title="Ver resumo">
        <i class="uil-eye"></i>
    </a>

    @if($inv->status === 'contando' && $inv->total_itens == $inv->contados)
        <form action="{{ route('inventario.efetivar', $inv->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-success btn-sm" onclick="return confirm('Deseja efetivar esse inventário?')">
                <i class="uil-check-circle"></i>
            </button>
        </form>
    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
