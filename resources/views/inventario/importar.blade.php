@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">📦 Importar Lista de SKUs para Contagem</h4>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @elseif (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('inventario.gerar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="lista_skus" class="form-label"><strong>Cole os dados (Material, Centro, Descrição)</strong></label>
                    <textarea name="lista_skus" id="lista_skus" class="form-control" rows="15"
                        placeholder="Exemplo:
3000.AT.003	HY09	ACOPLAMENTO OPT/STAR TURBO C/ TRIAC (AT)
3000.AT.006	HY09	ACOPLAMENTO ND BLINDADA 6500W 220V (AT)
..."></textarea>
                    <div class="form-text">
                        Use <code>[TAB]</code> como separador entre as colunas.
                    </div>
                </div>

                <button type="submit" class="btn btn-success">
                    <i class="uil-upload"></i> Gerar Listagem de Inventário
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
