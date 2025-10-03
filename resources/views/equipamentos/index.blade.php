@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Equipamentos</h1>
    <div class="row text-center mb-5 justify-content-center">
    @php
        $icones = [
            'Empilhadeira' => 'empilhadeira.png',
            'Televisão' => 'tv.png',
            'Notebook' => 'notebook.png',
            'PC' => 'pc.png',
            'Celular' => 'celular.png',
            'Coletor' => 'coletor.png',
            'Máquina de limpar piso' => 'maquina_limpar_piso.png',
            'Paleteira (2t)' => 'paleteira_2t.png',
        ];
    @endphp

    @foreach ($icones as $tipo => $img)
        <div class="col-6 col-sm-3 col-md-3 col-lg-2 mb-4 d-flex flex-column align-items-center">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.2);">
                <img src="{{ asset('assets/equipamentos/' . $img) }}"
                     alt="{{ $tipo }}"
                     style="width: 80%; height: auto;">
            </div>
            <div class="mt-2 text-uppercase fw-bold small">{{ $tipo }}</div>
            <div class="fw-bold fs-5">{{ $resumo[$tipo] ?? 0 }}</div>
        </div>
    @endforeach
</div>


    <a href="{{ route('equipamentos.create') }}" class="btn btn-primary mb-3">Novo Equipamento</a>
    <form method="GET" class="row mb-4">
        <div class="col">
            <input type="text" name="tipo" class="form-control" placeholder="Tipo" value="{{ request('tipo') }}">
        </div>
        <div class="col">
            <select name="status" class="form-control">
                <option value="">-- Status --</option>
                <option value="ativo">Ativo</option>
                <option value="manutenção">Manutenção</option>
                <option value="inativo">Inativo</option>
            </select>
        </div>
        <div class="col">
            <input type="text" name="localizacao" class="form-control" placeholder="Localização" value="{{ request('localizacao') }}">
        </div>
        <div class="col">
            <button class="btn btn-secondary" type="submit">Filtrar</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Modelo</th>
                <th>Patrimonio</th>
                <th>Status</th>
                <th>Localização</th>
                <th>Responsável</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($equipamentos as $eq)
            <tr>
                <td>{{ $eq->tipo }}</td>
                <td>{{ $eq->modelo }}</td>
                <td>{{ $eq->patrimonio }}</td>
                <td>{{ ucfirst($eq->status) }}</td>
                <td>{{ $eq->localizacao }}</td>
                <td>{{ $eq->responsavel }}</td>
                <td>
                    <a href="{{ route('equipamentos.edit', $eq->id) }}" class="btn btn-sm btn-warning">Editar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
   
    <div class="mt-3">
        <a href="{{ route('equipamentos.export.excel') }}" class="btn btn-success">Exportar Excel</a>
        <a href="{{ route('equipamentos.export.pdf') }}" class="btn btn-danger">Exportar PDF</a>
    </div>
</div>
@endsection
