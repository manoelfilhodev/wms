
@extends($layout)

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Programar Montagem de Kit</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Formulário para programar kit --}}
    <form method="POST" action="{{ route('kit.programar.store') }}">
        @csrf

        <div class="mb-3">
            <label for="codigo_material" class="form-label">Código do Kit</label>
            <input type="text" id="codigo_material" name="codigo_material" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quantidade_programada" class="form-label">Quantidade a Produzir</label>
            <input type="number" id="quantidade_programada" name="quantidade_programada" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="data_montagem" class="form-label">Data da Montagem</label>
            <input type="date" id="data_montagem" name="data_montagem" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Programar</button>
       
    </form>

    <hr class="my-4">
    
    <h5 class="mb-3">Programações Existentes</h5>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Código Material</th>
                <th>Qtd Programada</th>
                <th>Data Montagem</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($kits ?? []) as $kit)
    <tr>
        <td>{{ $kit->id }}</td>
        <td>{{ $kit->codigo_material }}</td>
        <td>{{ $kit->quantidade_programada }}</td>
        <td>{{ \Carbon\Carbon::parse($kit->data_montagem)->format('d/m/Y') }}</td>
        <td>
            ...
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">Nenhuma programação encontrada</td>
    </tr>
@endforelse
        </tbody>
    </table>
</div>
@endsection
