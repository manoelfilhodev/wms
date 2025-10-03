@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Importar Demandas em Lote</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('demandas.import') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="planilha" class="form-label">Cole aqui os dados da planilha</label>
            <textarea name="planilha" id="planilha" class="form-control" rows="12" 
                placeholder="Cole aqui os dados copiados do Excel (inclusive com cabeçalho)"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Importar Demandas</button>
    </form>
</div>
@endsection
