@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Produtos</h4>
        <a href="{{ route('produtos.create') }}" class="btn btn-primary">Novo Produto</a>
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('produtos.index') }}" class="mb-3">
        <div class="row g-2">
            <div class="col-md-2">
                <input type="text" name="sku" value="{{ request('sku') }}" class="form-control" placeholder="SKU">
            </div>
            <div class="col-md-3">
                <input type="text" name="ean" value="{{ request('ean') }}" class="form-control" placeholder="EAN">
            </div>
            <div class="col-md-4">
                <input type="text" name="descricao" value="{{ request('descricao') }}" class="form-control" placeholder="Descrição">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success">Filtrar</button>
                <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Limpar</a>
            </div>
        </div>
    </form>

    {{-- Mensagem de sucesso --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tabela --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>EAN</th>
                        <th>Descrição</th>
                        <th>Estoque</th>
                        <th>Lastro</th>
                        <th>Camada</th>
                        <th>Paletização</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produtos as $produto)
                        <tr>
                            <td>{{ mb_strtoupper($produto->sku) }}</td>
                            <td>{{ $produto->ean }}</td>
                            <td>{{ $produto->descricao }}</td>
                            <td>{{ $produto->quantidade_estoque }}</td>
                            <td>{{ $produto->lastro }}</td>
                            <td>{{ $produto->camada }}</td>
                            <td>{{ $produto->paletizacao }}</td>
                            <td>
                                <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST" style="display:inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este produto?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Nenhum produto encontrado</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Paginação --}}
    @if ($produtos instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-3">
          
        </div>
    @endif
</div>
@endsection
