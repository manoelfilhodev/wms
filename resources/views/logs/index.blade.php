@extends('layouts.app')

@section('content')


<div class="container-fluid">
   @include('partials.breadcrumb-auto')
    <div class="d-flex justify-content-between align-items-center mb-3">
         
        <h4 class="mb-0">Logs de Usuários</h4>
        
        <div>
            
            <a href="{{ route('logs.export.excel', request()->query()) }}" class="btn btn-success btn-sm me-2">
                <i class="mdi mdi-file-excel"></i> Exportar Excel
            </a>
            <a href="{{ route('logs.export.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
                <i class="mdi mdi-file-pdf"></i> Exportar PDF
            </a>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="usuario" class="form-control" placeholder="Nome do usuário" value="{{ request('usuario') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="acao" class="form-control" placeholder="Ação realizada" value="{{ request('acao') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="data" class="form-control" value="{{ request('data') }}">
        </div>
        <div class="col-md-3">
            <select name="unidade" class="form-control">
                <option value="">Todas as unidades</option>
                @foreach ($unidades as $unidade)
                    <option value="{{ $unidade->nome }}" {{ request('unidade') == $unidade->nome ? 'selected' : '' }}>
                        {{ $unidade->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary w-100">
                <i class="mdi mdi-filter"></i> Filtrar
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Usuário</th>
                    <th>Unidade</th>
                    <th>Ação</th>
                    <th>Dados</th>
                    <th>IP</th>
                    <th>Navegador</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr style="font-size: 12px">
                        <td>{{ mb_strtoupper($log->usuario->nome ?? '---') }}</td>
                        <td>{{ $log->unidade->nome ?? '---' }}</td>
                        <td>{{ mb_strtoupper($log->acao) }}</td>
                        <td style="max-width: 300px; word-break: break-word;">{{ $log->dados }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td style="max-width: 200px;">{{ $log->navegador }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Nenhum log encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>
@endsection