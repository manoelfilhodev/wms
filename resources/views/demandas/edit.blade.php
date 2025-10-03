@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Editar Demanda</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('demandas.update', $demanda->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">FO</label>
            <input type="text" name="fo" class="form-control" value="{{ $demanda->fo }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <input type="text" name="cliente" class="form-control" value="{{ $demanda->cliente }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Transportadora</label>
            <input type="text" name="transportadora" class="form-control" value="{{ $demanda->transportadora }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Doca</label>
            <input type="text" name="doca" class="form-control" value="{{ $demanda->doca }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="RECEBIMENTO" {{ $demanda->tipo == 'RECEBIMENTO' ? 'selected' : '' }}>Recebimento</option>
                <option value="EXPEDICAO" {{ $demanda->tipo == 'EXPEDICAO' ? 'selected' : '' }}>Expedição</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" name="quantidade" class="form-control" value="{{ $demanda->quantidade }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Peso</label>
                <input type="number" step="0.01" name="peso" class="form-control" value="{{ $demanda->peso }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Valor da Carga</label>
                <input type="number" step="0.01" name="valor_carga" class="form-control" value="{{ $demanda->valor_carga }}">
            </div>
        </div>
        
        <div class="mb-3">
    <label for="veiculo" class="form-label">Veículo</label>
    <input type="text" class="form-control" id="veiculo" name="veiculo" 
           value="{{ old('veiculo', $demanda->veiculo) }}">
</div>

<div class="mb-3">
    <label for="modelo_veicular" class="form-label">Modelo Veicular</label>
    <input type="text" class="form-control" id="modelo_veicular" name="modelo_veicular" 
           value="{{ old('modelo_veicular', $demanda->modelo_veicular) }}">
</div>

<div class="mb-3">
    <label for="motorista" class="form-label">Motorista</label>
    <input type="text" class="form-control" id="motorista" name="motorista" 
           value="{{ old('motorista', $demanda->motorista) }}">
</div>


        <div class="mb-3">
            <label class="form-label">Hora Agendada</label>
            <input type="time" name="hora_agendada" class="form-control" value="{{ $demanda->hora_agendada }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Entrada</label>
                <input type="time" name="entrada" class="form-control" value="{{ $demanda->entrada }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Saída</label>
                <input type="time" name="saida" class="form-control" value="{{ $demanda->saida }}">
            </div>
        </div>
        <h5 class="mt-4">Histórico de Status</h5>
<ul class="list-group">
   @forelse($demanda->history ?? [] as $h)
    <li class="list-group-item">
        <strong>{{ $h->status }}</strong>
        em {{ $h->created_at->format('d/m/Y H:i') }}
        @if($h->user)
            por {{ $h->user->nome }}
        @endif
    </li>
@empty
    <li class="list-group-item text-muted">Nenhum histórico registrado ainda.</li>
@endforelse
</ul><br>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="{{ route('demandas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
