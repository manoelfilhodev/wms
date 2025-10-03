@extends($layout)

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Central de Montagem de Kits</h4>

    <div class="row">
        <!-- Lançar nova montagem --> 
        <div class="col-md-4 mb-3">
            <a href="{{ route('kit.programar') }}" class="card text-center text-decoration-none text-dark shadow-sm h-100">
                <div class="card-body">
                    <i class="mdi mdi-playlist-plus mdi-48px text-success"></i>
                    <h5 class="mt-2">Programação Kit</h5>
                </div>
            </a>
        </div>

        <!-- Apontamentos de kits -->
        <div class="col-md-4 mb-3">
            <a href="{{ route('kit.apontar') }}" 
               class="card text-center text-decoration-none text-dark shadow-sm h-100">
                <div class="card-body">
                    <i class="mdi mdi-format-list-bulleted mdi-48px text-info"></i>
                    <h5 class="mt-2">Apontamentos</h5>
                </div>
            </a>
        </div>

        <!-- Pendências de apontamento -->
        <div class="col-md-4 mb-3">
            <a href="{{ route('kit.pendencias') }}" 
               class="card text-center text-decoration-none text-dark shadow-sm h-100">
                <div class="card-body">
                    <i class="mdi mdi-alert-circle mdi-48px text-danger"></i>
                    <h5 class="mt-2">Pendências de Apontamento</h5>
                </div>
            </a>
        </div>

        @php
            $temKitHoje = false;
        @endphp

        @if ($kits->contains(function ($kit) {
            return \Carbon\Carbon::parse($kit->data_montagem)->isToday();
        }))
            <div class="col-md-4 mb-3">
                <a href="{{ route('kit.editar', $kits->firstWhere('data_montagem', \Carbon\Carbon::today()->toDateString())->id) }}"
                   class="card text-center text-decoration-none text-dark shadow-sm h-100">
                    <div class="card-body">
                        <i class="mdi mdi-pencil-box-outline mdi-48px text-warning"></i>
                        <h5 class="mt-2">Editar Programação</h5>
                    </div>
                </a>
            </div>
        @endif

        @auth
            @if(Auth::user()->tipo === 'admin')
                <div class="col-md-4 mb-3">
                    <a href="{{ route('kit.relatorio') }}" class="card text-center text-decoration-none text-dark shadow-sm h-100">
                        <div class="card-body">
                            <i class="mdi mdi-chart-bar mdi-48px text-primary"></i>
                            <h5 class="mt-2">Relatório de Kits</h5>
                        </div>
                    </a>
                </div>
            @endif
        @endauth

        <!-- Etiquetas de kits -->
        <div class="col-md-4 mb-3">
            <a href="{{ route('kit.etiquetas') }}" 
               class="card text-center text-decoration-none text-dark shadow-sm h-100">
                <div class="card-body">
                    <i class="mdi mdi-tag-multiple mdi-48px text-success"></i>
                    <h5 class="mt-2">Etiquetas</h5>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
