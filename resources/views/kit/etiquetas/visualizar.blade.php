@extends($layout)

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Etiquetas do Kit #{{ $kit->id }} ({{ $kit->codigo_material }})</h4>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Qtd</th>
                <th>UA</th>
                <th>Data Geração</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($etiquetas as $etiqueta)
                <tr>
                    <td>{{ $etiqueta->id }}</td>
                    <td>{{ $etiqueta->quantidade }}</td>
                    <td>{{ $etiqueta->palete_uid }}</td>
                    <td>{{ \Carbon\Carbon::parse($etiqueta->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('kits.etiquetas.reimprimir', $etiqueta->id) }}" 
                           target="_blank" class="btn btn-sm btn-warning">Reimprimir</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Nenhuma etiqueta gerada</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
