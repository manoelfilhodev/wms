@extends('layouts.app')

@section('content')

<div class="container-fluid">
   @include('partials.breadcrumb-auto')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Saldo Atual de SKUs</h4>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="sku" class="form-control" placeholder="SKU" value="{{ request('sku') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="descricao" class="form-control" placeholder="Descrição" value="{{ request('descricao') }}">
        </div>
        <div class="col-md-3">
            <input type="text" name="posicao" class="form-control" placeholder="Posição" value="{{ request('posicao') }}">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100">
                <i class="mdi mdi-filter"></i> Filtrar
            </button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>SKU</th>
                    <th>Descrição</th>
                    <th>Posição</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($saldos as $s)
                    <tr style="font-size: 13px">
                        <td>{{ $s->sku }}</td>
                        <td>{{ $s->descricao }}</td>
                        <td>{{ $s->codigo_posicao }}</td>
                        <td>{{ $s->quantidade }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">Nenhum saldo encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection