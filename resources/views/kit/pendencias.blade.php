@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Pendências de Apontamento</h4>

    @if($pendencias->isEmpty())
        <div class="alert alert-success text-center">
            Nenhuma pendência encontrada. 🎉
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Etiqueta</th>
                        <th>SKU</th>
                        
                        <th class="text-center">Quantidade</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Gerado em</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendencias as $p)
                        <tr>
                            <td class="text-center">{{ $p->palete_uid }}</td>
                            <td>{{ $p->codigo_material ?? '-' }}</td>
                            <td class="text-center">{{ $p->quantidade ?? 0 }}</td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $p->status ?? 'GERADO' }}</span>
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
