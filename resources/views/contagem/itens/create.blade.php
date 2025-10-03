@extends($layout)

@section('title', 'Nova Contagem de Itens')

@section('content')
<div class="container">
    <h4 class="mb-4">➕ Nova Contagem de Itens (Obrigatório contar todos os 6)</h4>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('contagem.itens.storeMultiple') }}" method="POST">
        @csrf

        <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Material</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materiais as $m)
                    <tr>
                        <td>{{ $m->codigo_material }} - {{ $m->descricao }}</td>
                        <td>
                            <input type="number" 
                                   name="quantidades[{{ $m->codigo_material }}]" 
                                   class="form-control" 
                                   min="0" 
                                   required>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('contagem.itens.index') }}" class="btn btn-secondary">
                <i class="uil uil-arrow-left"></i> Voltar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="uil uil-save"></i> Salvar Contagem
            </button>
        </div>
    </form>
</div>
@endsection
