@extends('layouts.app')

@section('content')

<div class="container-fluid">
   @include('partials.breadcrumb-auto')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Gerenciamento de Posições</h4>
    </div>

    <form method="POST" action="{{ route('inventario.posicoes.salvar') }}" class="row g-2 mb-4">
        @csrf
        <div class="col-md-6">
            <input name="codigo_posicao" class="form-control" placeholder="Código da nova posição" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Criar</button>
        </div>
    </form>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="codigo" class="form-control" placeholder="Código da posição" value="{{ request('codigo') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-control">
                <option value="">Todos os status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Ativa</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inativa</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">
                <i class="mdi mdi-filter"></i> Filtrar
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Posição</th>
                    <th>Status</th>
                    <th>Unidade</th>
                    <th>Criado em</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posicoes as $p)
                    <tr style="font-size: 13px">
                        <td>{{ $p->codigo_posicao }}</td>
                        <td>
                            <span class="badge bg-{{ $p->status ? 'success' : 'secondary' }}">
                                {{ $p->status ? 'Ativa' : 'Inativa' }}
                            </span>
                        </td>
                        <td>{{ $p->unidade->nome ?? '---' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Nenhuma posição encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection