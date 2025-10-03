@extends($layout)

@section('content')
<div class="container">
    <h4>Painel de Recebimento - Notas Fiscais</h4>

    <a href="{{ route('setores.recebimento.create') }}" class="btn btn-primary mb-3">Iniciar Recebimento</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Ações</th>
                <th>Iniciar</th>
                <th>NF</th>
                <th>Fornecedor</th>
                <th>Data</th>
                <th>Status</th>
                <th>Progresso</th>
                <th>Etiquetas</th>
            </tr>
        </thead>
        <tbody>
        @foreach($recebimentos as $recebimento)
            @php
                $totalItens = $recebimento->itens->count();
                $armazenados = $recebimento->itens->where('status', 'armazenado')->count();
                $percentual = $totalItens > 0 ? round(($armazenados / $totalItens) * 100) : 0;

                $temDivergencia = DB::table('_tb_recebimento_itens')
                    ->where('recebimento_id', $recebimento->id)
                    ->where('divergente', 1)
                    ->exists();

                $temAvaria = DB::table('_tb_recebimento_itens')
                    ->where('recebimento_id', $recebimento->id)
                    ->where('avariado', 1)
                    ->exists();

                $nivel = strtolower(Auth::user()->nivel);
            @endphp
            <tr>
                <td>
                    <a href="{{ route('setores.conferencia.telaFotoInicio', $recebimento->id) }}" class="btn btn-primary btn-sm">
                        Iniciar Conferência
                    </a>

                    @if($recebimento->status === 'conferido' && in_array($nivel, ['admin', 'documental', 'recebimento']))
                        <a href="{{ route('setores.conferencia.formRessalva', $recebimento->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-chat-left-dots"></i> Adicionar Ressalva
                        </a>
                        <a href="{{ route('setores.conferencia.relatorio', $recebimento->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                    @endif
                </td>
                <td><span class="text-success">✔</span></td>
                <td>{{ $recebimento->nota_fiscal }}</td>
                <td>{{ $recebimento->fornecedor }}</td>
                <td>{{ \Carbon\Carbon::parse($recebimento->data_recebimento)->format('d/m/Y') }}</td>
                <td>
                    @if ($temDivergencia)
                        <span class="badge bg-danger">Divergência</span>
                    @endif
                    @if ($temAvaria)
                        <span class="badge bg-warning text-dark">Avaria</span>
                    @endif
                </td>
                <td>
                    <div class="progress">
                        <div class="progress-bar bg-{{ $percentual == 100 ? 'success' : ($percentual > 0 ? 'info' : 'secondary') }}" style="width: {{ $percentual }}%;">
                            {{ $percentual }}%
                        </div>
                    </div>
                </td>
                <td>
                    <button class="btn btn-success btn-sm" onclick="abrirModalEtiquetas({{ $recebimento->id }})">
                        <i class="fa fa-tags"></i> Etiquetas
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Modal Etiquetas --}}
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

</div>

<script>
function abrirModalEtiquetas(recebimentoId, itemId = null) {
    let urlImprimirTudo = "{{ route('recebimento.imprimirTudo', ':id') }}".replace(':id', recebimentoId);
    document.getElementById('btnImprimirTudo').href = urlImprimirTudo;

    if (itemId) {
        let urlReimprimir = "{{ route('recebimento.reimprimir', ':itemId') }}".replace(':itemId', itemId);
        document.getElementById('btnReimprimir').href = urlReimprimir;
        document.getElementById('btnReimprimir').style.display = 'inline-block';
    } else {
        document.getElementById('btnReimprimir').style.display = 'none';
    }

    $('#modalEtiquetas').modal('show');
}
</script>
@endsection
