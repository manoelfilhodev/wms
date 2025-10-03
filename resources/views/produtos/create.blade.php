@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Novo Produto</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('produtos.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" required>
                </div>

                <div class="mb-3">
                    <label>EAN</label>
                    <input type="text" name="ean" class="form-control" value="{{ old('ean') }}" placeholder="Código de barras (EAN)">
                </div>

                <div class="mb-3">
                    <label>Descrição</label>
                    <input type="text" name="descricao" class="form-control" value="{{ old('descricao') }}" required>
                </div>

                <div class="mb-3">
                    <label>Quantidade Estoque</label>
                    <input type="number" name="quantidade_estoque" class="form-control" value="{{ old('quantidade_estoque', 0) }}" required>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Lastro</label>
                        <input type="number" name="lastro" class="form-control" value="{{ old('lastro', 0) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Camada</label>
                        <input type="number" name="camada" class="form-control" value="{{ old('camada', 0) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Paletização</label>
                        <input type="number" name="paletizacao" class="form-control" value="{{ old('paletizacao', 0) }}" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ route('produtos.index') }}" class="btn btn-secondary">Voltar</a>
            </form>
        </div>
    </div>
</div>
@endsection
