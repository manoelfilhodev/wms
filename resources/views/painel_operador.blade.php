@extends($layout)

@section('content')
<div class="container py-4">
    <h2 class="text-white text-center mb-5">Painel do Operador</h2>

    <div class="row justify-content-center">
        
        <div class="col-12 col-md-6 mb-3">
                    <a href="{{ route('setores.recebimento.painel') }}" class="btn btn-dark btn-lg w-100 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-warehouse me-2 fs-4"></i> Recebimento
                    </a>
        </div>
        <div class="col-12 col-md-6 mb-3">
                    <a href="{{ route('kit.index') }}" class="btn btn-dark btn-lg w-100 d-flex align-items-center justify-content-center">
                        <i class="mdi mdi-warehouse me-2 fs-4"></i> Montagem Kits
                    </a>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <a href="{{ route('armazenagem.index') }}" class="btn btn-dark btn-lg w-100 d-flex align-items-center justify-content-center">
                <i class="mdi mdi-warehouse me-2 fs-4"></i> Armazenagem
            </a>
        </div>

        <div class="col-12 col-md-6 mb-3">
            <a href="{{ route('separacao.index') }}" class="btn btn-dark btn-lg w-100 d-flex align-items-center justify-content-center">
                <i class="mdi mdi-clipboard-text-outline me-2 fs-4"></i> Separação
            </a>
        </div>
        
        <div class="col-12 col-md-6 mb-3">
            <a href="{{ route('contagem.itens.index') }}" class="btn btn-dark btn-lg w-100 d-flex align-items-center justify-content-center">
                 <i class="mdi mdi-package-variant me-2 fs-4"></i> Contagem Paletes
            </a>
        </div>

        
    </div>
</div>
@endsection
