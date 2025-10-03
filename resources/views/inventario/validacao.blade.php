@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Validação da Contagem - Inventário #{{ $id_inventario }}</h4>

    <table class="table table-sm table-bordered">
        <thead class="table-dark">
            <tr>
                <th>SKU</th>
                <th>Descrição</th>
                <th>Posição</th>
                <th>Sistema</th>
                <th>Físico</th>
                <th>Diferença</th>
                <th>Ajuste</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
                <tr class="{{ $item->necessita_ajuste ? 'table-warning' : 'table-success' }}">
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->descricao }}</td>
                    <td>{{ $item->posicao }}</td>
                    <td>{{ $item->quantidade_sistema }}</td>
                    <td>{{ $item->quantidade_fisica }}</td>
                    <td>{{ $item->diferenca }}</td>
                    <td>{{ $item->tipo_ajuste }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">Voltar ao Dashboard</a>
</div>
@endsection
