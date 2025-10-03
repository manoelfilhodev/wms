@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Cadastro de Multipack</h4>
    <form action="{{ route('multipack.store') }}" method="POST">
        @csrf
        <table class="table table-bordered" id="multipack-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Descrição</th>
                    <th>Fator Embalagem</th>
                    <th><button type="button" class="btn btn-success" onclick="addRow()">+</button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="text" name="sku[]" class="form-control" required></td>
                    <td><input type="text" name="descricao[]" class="form-control" required></td>
                    <td><input type="number" name="fator_embalagem[]" class="form-control" required></td>
                    <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">-</button></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<script>
    function addRow() {
        const table = document.getElementById("multipack-table").getElementsByTagName('tbody')[0];
        const newRow = table.rows[0].cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        table.appendChild(newRow);
    }

    function removeRow(button) {
        const row = button.closest('tr');
        const table = document.getElementById("multipack-table").getElementsByTagName('tbody')[0];
        if (table.rows.length > 1) {
            row.remove();
        }
    }
</script>
@endsection
