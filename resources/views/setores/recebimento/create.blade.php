@extends($layout)

@section('content')
<div class="container">
    <h4>Recebimento de Materiais</h4>

    <form action="{{ route('setores.recebimento.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

       
        <div class="row">
            <div class="col-md-9 mb-3">
                <label>Motorista</label>
                <input type="text" name="motorista" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label>Placa</label>
                <input type="text" name="placa" class="form-control">
            </div>
           
        </div>

        <div class="mb-3">
            <label>Tipo Carga</label>
            <select name="tipo" class="form-select" required>
                <option value="">Selecione...</option>
                <option value="LOUÇAS">LOUÇAS</option>
                <option value="METAIS">METAIS</option>
                <option value="KIT">KIT</option>
            </select>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Horário Janela</label>
                <input type="time" name="horario_janela" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Horário Chegada</label>
                <input type="time" name="horario_chegada" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>Doca</label>
                <select name="doca" class="form-select">
                    <option value="">Selecione uma doca...</option>
                    <option value="1">Doca 1</option>
                    <option value="2">Doca 2</option>
                    <option value="3">Doca 3</option>
                    <option value="4">Doca 4</option>
                    <option value="5">Doca 5</option>
                    <option value="6">Doca 6</option>
                    <option value="7">Doca 7</option>
                    <option value="8">Doca 8</option>
                    <option value="9">Doca 9</option>
                    <option value="10">Doca 10</option>
                    <option value="11">Doca 11</option>
                    <option value="12">Doca 12</option>
                    <option value="13">Doca 13</option>
                    <option value="14">Doca 14</option>
                    <option value="15">Doca 15</option>
                    
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Inserir XML NFe</label>
            <input type="file" name="xml_nfe" class="form-control">
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('setores.recebimento.painel') }}" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>
@endsection
