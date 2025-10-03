@extends($layout)
@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Central de Separação</h4>

    <div class="row">
        <!-- Sempre visível -->
        <div class="col-md-4 mb-3">
            <a href="{{ route('pedidos.index') }}" class="card text-center text-decoration-none text-dark shadow-sm h-100">
                <div class="card-body">
                    <div class="position-relative">
                        <i class="mdi mdi-play-circle-outline mdi-48px text-warning"></i>
                    
                        @if($pedidosPendentes > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $pedidosPendentes }}
                            </span>
                        @endif
                    </div>
                    
                    <h5 class="mt-2">Pedidos Pendentes</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('separacoes.index') }}" class="card text-center text-decoration-none text-dark shadow-sm h-100">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted mdi-48px text-info"></i>
                    <h5 class="mt-2">Separações em Andamento</h5>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
    <a href="{{ route('separacoes.linha.manual') }}" class="card text-center text-decoration-none text-dark shadow-sm h-100">
        <div class="card-body">
            <i class="mdi mdi-factory mdi-48px text-success"></i>
            <h5 class="mt-2">Separação Linha</h5>
        </div>
    </a>
</div>




        

        @auth
            @if(Auth::user()->tipo === 'admin')
                <div class="col-md-4 mb-3">
                    <a href="{{ route('pedidos.create') }}" class="card text-center text-decoration-none text-dark shadow-sm h-100">
                        <div class="card-body">
                            <i class="mdi mdi-playlist-plus mdi-48px text-success"></i>
                            <h5 class="mt-2">Inserir Novo Pedido</h5>
                        </div>
                    </a>
                </div>
                

                <div class="col-md-4 mb-3">
                    <a href="{{ route('relatorios.separacoes') }}" class="card text-center text-decoration-none text-dark shadow-sm h-100">
                        <div class="card-body">
                            <i class="mdi mdi-chart-bar mdi-48px text-primary"></i>
                            <h5 class="mt-2">Relatório de Separações</h5>
                        </div>
                    </a>
                </div>
            @endif
        @endauth
    </div>
</div>

@endsection
