@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Relatórios por Setor</h4>

    <div class="row">
        <div class="col-md-4">
            <a href="{{ route('relatorios.separacoes') }}" class="card text-center text-decoration-none text-dark shadow-sm">
                <div class="card-body">
                    <i class="mdi mdi-package-variant-closed mdi-48px text-primary"></i>
                    <h5 class="mt-2">Relatório de Separações</h5>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('relatorios.armazenagem') }}" class="card text-center text-decoration-none text-dark shadow-sm disabled">
                <div class="card-body">
                    <i class="mdi mdi-warehouse mdi-48px text-primary"></i>
                    <h5 class="mt-2">Relatório de Armazenagem</h5>
                </div>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="{{ route('kit.relatorio') }}" class="card text-center text-decoration-none text-dark shadow-sm disabled">
                <div class="card-body">
                    <i class="mdi mdi-chart-bar mdi-48px text-primary"></i>
                    <h5 class="mt-2">Relatório de Kits</h5>
                </div>
            </a>
        </div>
      
    

        <!-- Adicione mais setores conforme necessário -->
    </div>
</div>
@endsection
