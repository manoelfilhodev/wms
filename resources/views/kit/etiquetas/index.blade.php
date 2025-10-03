@extends($layout)

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Lista de Kits</h4>

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
            @foreach($kits as $kit)
                <tr>
                    <td>{{ $kit->id }}</td>
                    <td>{{ $kit->codigo_material }}</td>
                    <td>{{ $kit->quantidade_programada }}</td>
                    <td>{{ \Carbon\Carbon::parse($kit->data_montagem)->format('d/m/Y') }}</td>
                    <td>
                       <a href="javascript:void(0);" 
   class="btn btn-success btn-sm"
   onclick="abrirModalEtiquetas({{ $kit->id }})">
   <i class="fa fa-tags"></i> Etiquetas
</a>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Modal Etiquetas -->
<div class="modal fade" id="modalEtiquetas" tabindex="-1" role="dialog" aria-labelledby="modalEtiquetasLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEtiquetasLabel">Impressão de Etiquetas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Selecione uma das opções abaixo para imprimir as etiquetas:
            </div>
            <div class="modal-footer">
                <a href="#" id="btnImprimirTudo" class="btn btn-primary" target="_blank">
                    <i class="fa fa-print"></i> Imprimir Tudo
                </a>
                <a href="#" id="btnReimprimir" class="btn btn-warning" target="_blank" style="display:none;">
                    <i class="fa fa-refresh"></i> Reimprimir
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModalEtiquetas(kitId, etiquetaId = null) {
    // Imprimir tudo
    let urlImprimirTudo = "{{ route('kits.etiquetas.imprimirTudo', ':id') }}".replace(':id', kitId);
    document.getElementById('btnImprimirTudo').href = urlImprimirTudo;

    // Reimprimir uma etiqueta específica
    if (etiquetaId) {
        let urlReimprimir = "{{ route('kits.etiquetas.reimprimir', ':id') }}".replace(':id', etiquetaId);
        document.getElementById('btnReimprimir').href = urlReimprimir;
        document.getElementById('btnReimprimir').style.display = 'inline-block';
    } else {
        document.getElementById('btnReimprimir').style.display = 'none';
    }

    $('#modalEtiquetas').modal('show');
}
</script>

@endsection
