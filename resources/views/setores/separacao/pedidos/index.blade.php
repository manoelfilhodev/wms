@extends($layout)
@section('content')
<div class="container">
    <h4>Pedidos Pendentes</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Número</th>
                <th>FO</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pedidos as $pedido)
                <tr>
                    <td>{{ $pedido->numero_pedido }}</td>
                    <td>{{ $pedido->itens->first()->fo ?? 'N/D' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i') }}</td>
                    <td><span class="badge bg-secondary">Pendente</span></td>
                    <td>
                        <form method="POST" action="{{ route('separacoes.iniciar', $pedido->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">Iniciar Separação</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Nenhum pedido pendente encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
