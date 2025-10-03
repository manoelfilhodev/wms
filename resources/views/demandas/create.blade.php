@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Lançar Nova Demanda</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('demandas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">FO</label>
            <input type="text" name="fo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Cliente</label>
            <input type="text" name="cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Transportadora</label>
            <input type="text" name="transportadora" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Doca</label>
            <input type="text" name="doca" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="RECEBIMENTO">Recebimento</option>
                <option value="EXPEDICAO">Expedição</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" name="quantidade" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Peso</label>
                <input type="number" step="0.01" name="peso" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Valor da Carga</label>
                <input type="number" step="0.01" name="valor_carga" class="form-control">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Hora Agendada</label>
            <input type="time" name="hora_agendada" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
@endsection
