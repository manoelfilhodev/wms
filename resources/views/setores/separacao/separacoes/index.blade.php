@extends($layout)

@section('content')
<div class="container">
    <h4 class="mb-4">Separações em Andamento</h4>

    @if($separacoes->isEmpty())
        <div class="alert alert-info">Nenhum pedido em separação no momento.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Ação</th>
                    <th>FO</th>
                    <th>Pedido</th>
                    
                    <th>Data</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach($separacoes as $pedido)
                    @php
                        $primeiroNaoConferido = $pedido->itensSeparacao->where('conferido', false)->first();
                    @endphp
                     
                    <tr>
                        <td>
                            @if($primeiroNaoConferido)
                                <a href="{{ route('separacoes.form', $primeiroNaoConferido->id) }}" class="btn btn-sm btn-primary">
                                    Separar Agora
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $pedido->itensSeparacao->first()->fo ?? '-' }}</td>
                        <td>#PED{{ $pedido->numero_pedido }}</td>
                        
                        <td>{{ $pedido->created_at ? $pedido->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @if($primeiroNaoConferido)
                                <span class="badge bg-warning text-dark">Em Andamento</span>
                            @else
                                <span class="badge bg-success">Finalizado</span>
                            @endif
                        </td>
                       
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
