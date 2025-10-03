@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Contagens Realizadas</h4>
    
    @if(!empty($contagens) && count($contagens))
    <p class="text-muted">
        Total de contagens exibidas: <strong>{{ count($contagens) }}</strong>
    </p>
@endif


    <div class="table-responsive mt-4">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Ficha</th>
                    <th>SKU</th>
                    <th>Quantidade</th>
                    <th>Contado por</th>
                    <th>Data/Hora</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contagens as $contagem)
                    <tr>
                        <td>{{ $contagem->ficha }}</td>
                        <td>{{ $contagem->sku }}</td>
                        <td>{{ $contagem->quantidade }}</td>
                        <td>{{ $contagem->usuario }}</td>
                        <td>{{ \Carbon\Carbon::parse($contagem->data_hora)->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Nenhuma contagem registrada ainda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
