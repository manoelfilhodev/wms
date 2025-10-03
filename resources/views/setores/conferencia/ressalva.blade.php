@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Ressalva Pós-Conferência - NF {{ $recebimento->nota_fiscal }}</h4>

    <form method="POST" action="{{ route('setores.conferencia.salvarRessalva', $recebimento->id) }}">
        @csrf
        <div class="mb-3">
            <label for="ressalva_assistente" class="form-label">Ressalva do Assistente</label>
            <textarea name="ressalva_assistente" class="form-control" rows="4" required>{{ $recebimento->ressalva_assistente }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('setores.recebimento.painel') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
