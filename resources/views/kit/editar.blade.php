@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Editar Programação do Kit</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('kit.atualizar', '') }}" id="formEditar">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="kit_id" class="form-label">Selecione o SKU do Dia</label>
            <select class="form-select" name="kit_id" id="kit_id" required>
                <option value="">-- Selecione --</option>
                @foreach ($kitsHoje as $kit)
                    <option value="{{ $kit->id }}"
                        data-qtd="{{ $kit->quantidade_programada }}"
                        data-date="{{ \Carbon\Carbon::parse($kit->data_montagem)->format('Y-m-d') }}">
                        {{ $kit->codigo_material }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="infoCampos" style="display: none;">
            <div class="mb-3">
                <label class="form-label">Quantidade Atual Programada</label>
                <input type="number" class="form-control" id="quantidade_atual" readonly>
            </div>

            <div class="mb-3">
                <label for="quantidade_programada" class="form-label">Nova Quantidade</label>
                <input type="number" class="form-control" name="quantidade_programada" required>
            </div>

            <div class="mb-3">
                <label for="data_montagem" class="form-label">Nova Data da Montagem</label>
                <input type="date" class="form-control" name="data_montagem" required>
            </div>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="{{ route('kit.programar') }}" class="btn btn-secondary">Cancelar</a>
            <form method="POST" action="{{ route('kit.deletar', '') }}" id="formDelete" style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger"
        onclick="return confirm('Tem certeza que deseja excluir esta programação?');">
        Excluir
    </button>
</form>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('kit_id').addEventListener('change', function () {
        const select = this;
        const id = select.value;
        const qtd = select.options[select.selectedIndex].dataset.qtd;
        const data = select.options[select.selectedIndex].dataset.date;

        if (id) {
            document.getElementById('infoCampos').style.display = 'block';
            document.getElementById('quantidade_atual').value = qtd;
            document.querySelector('input[name=quantidade_programada]').value = qtd;
            document.querySelector('input[name=data_montagem]').value = data;
            document.getElementById('formEditar').action = `{{ url('/kit/programar') }}/${id}`;
        } else {
            document.getElementById('infoCampos').style.display = 'none';
        }
    });
</script>
@endpush
