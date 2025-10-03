@extends($layout)

@section('content')


    <form action="{{ route('contagem.livre.salvar') }}" method="POST">
        @csrf

        <div class="mb-3">
    <label for="ficha" class="form-label">Número da Ficha:</label>
    <input type="text" class="form-control" id="ficha" name="ficha" required autofocus>
</div>

        <div class="mb-3">
            <label for="sku" class="form-label">SKU:</label>
            <input type="text" class="form-control" id="sku" name="sku" required>
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade:</label>
            <input type="number" class="form-control" id="quantidade" name="quantidade" required min="1">
        </div>
        
        @if(isset($mensagem))
    <div class="alert alert-success">
        {{ $mensagem }}
    </div>
@endif

        <button type="submit" class="btn btn-success">Salvar Contagem</button>
    </form>
</div>
@endsection
