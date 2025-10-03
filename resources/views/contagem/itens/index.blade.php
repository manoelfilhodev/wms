@extends($layout)

@section('title', 'Relatório - Contagem de Itens')

@section('content')
<div class="container">



    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">
                    <i class="uil uil-box text-warning"></i> Relatóriode Contagem de Itens
                </h5>
                 
                <div class="d-flex gap-2">
                    @auth
                    @if(Auth::user()->tipo === 'admin')
                    <a href="{{ route('contagem.itens.excel') }}" class="btn btn-success btn-sm">
                        <i class="uil uil-file-exclamation-alt"></i> Excel
                    </a>
                    @endif
                    @endauth
                </div>
            </div>

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Material</label>
                    <select name="codigo_material" class="form-control">
                        <option value="">-- Todos --</option>
                        @foreach(\App\Models\ItemContagem::orderBy('descricao')->get() as $m)
                            <option value="{{ $m->codigo_material }}" 
                                {{ request('codigo_material') == $m->codigo_material ? 'selected' : '' }}>
                                {{ $m->codigo_material }} - {{ $m->descricao }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Data Início</label>
                    <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Data Fim</label>
                    <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="uil uil-filter"></i> Filtrar
                    </button>
                </div>
            </form>

            <div class="row mt-2 mb-2">
                <div class="col-12">
                    <a href="{{ route('contagem.itens.create') }}" class="btn btn-primary btn-sm w-100">
                        <i class="uil uil-plus"></i> Nova Contagem
                    </a>
                </div>
            </div>
            
            @if($contagens->count())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Material</th>
                                <th>Quantidade</th>
                                <th>Responsável</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contagens as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->material->codigo_material }} - {{ $item->material->descricao }}</td>
                                    <td>{{ $item->quantidade }}</td>
                                    <td>{{ $item->usuario->nome ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->data_contagem)->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

           
            @else
                <div class="alert alert-warning mt-3">
                    Nenhuma contagem encontrada para os filtros aplicados.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
