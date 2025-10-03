@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Recebimentos Pendentes de Conferência</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NF</th>
                <th>Fornecedor</th>
                <th>Transportadora</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recebimentos as $rec)
                <tr>
                    <td>{{ $rec->nota_fiscal }}</td>
                    <td>{{ $rec->fornecedor }}</td>
                    <td>{{ $rec->transportadora }}</td>
                    <td>{{ \Carbon\Carbon::parse($rec->data_recebimento)->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-warning text-dark text-uppercase">{{ $rec->status }}</span>
                    </td>
                    <td>
                        <a href="{{ route('setores.conferencia.itens', $rec->id) }}" class="btn btn-sm btn-outline-primary">
                            Iniciar Conferência
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Nenhum recebimento pendente.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
