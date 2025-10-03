@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Conferência da Nota Fiscal: {{ $recebimento->nota_fiscal }}</h4>
    <p><strong>Fornecedor:</strong> {{ $recebimento->fornecedor }}</p>
    <p><strong>Transportadora:</strong> {{ $recebimento->transportadora }}</p>



    <form action="{{ route('setores.conferencia.contar', $recebimento->id) }}" method="POST" enctype="multipart/form-data">
        
        @csrf

        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Descrição</th>
                    <th>Ação</th>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ($itens as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->descricao }}</td>
                    <td>
                        @if($item->status != 'conferido')
                            <a href="{{ url("setores/conferencia/item/{$recebimento->id}/{$item->id}/conferir") }}" class="btn btn-primary">
    Conferir
</a>

                        @else
                            <span class="badge bg-success">Conferido</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </form>
    
    <div class="row">
        <!--    <div class="mb-3">-->
        <!--    <label>Foto Fim do Veículo</label>-->
        <!--    <input type="file" name="foto_fim_veiculo" accept="image/*" class="form-control" required>-->
        <!--</div>-->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalFecharConferencia">
    Fechar Conferência
</button>

            <!--<div class="col-6 text-end mt-3">-->
            <!--    <a href="{{ route('setores.conferencia.relatorio', $recebimento->id) }}" class="btn btn-outline-dark" target="_blank">-->
            <!--        Gerar Relatório PDF-->
            <!--    </a>-->
            <!--</div>-->
        </div>
    <!-- Modal -->
<div class="modal fade" id="modalFecharConferencia" tabindex="-1" aria-labelledby="modalFecharConferenciaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <form method="POST" action="{{ route('setores.conferencia.finalizar', $recebimento->id) }}" enctype="multipart/form-data">
    @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Relatório de Conferência Cega</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-2"><strong>Rec. Doc</strong><input type="text" class="form-control" value="{{ $recebimento->id }}" readonly></div>
            <div class="col-md-3"><strong>Data Recebimento</strong><input type="text" class="form-control" value="{{ $recebimento->data_recebimento }}" readonly></div>
            <div class="col-md-2"><strong>NF</strong><input type="text" class="form-control" value="{{ $recebimento->nota_fiscal }}" readonly></div>
            <div class="col-md-2"><strong>Tipo Carga</strong><input type="text" class="form-control" value="{{ $recebimento->tipo }}" readonly></div>
          </div>

          <div class="row mb-3">
            <div class="col-md-3"><strong>Transportadora</strong><input type="text" class="form-control" value="{{ $recebimento->transportadora }}" readonly></div>
            <div class="col-md-2"><strong>Placa</strong><input type="text" class="form-control" value="{{ $recebimento->placa }}" readonly></div>
            <div class="col-md-2"><strong>Doca</strong><input type="text" class="form-control" value="{{ $recebimento->doca }}" readonly></div>
          </div>

          <div class="row mb-3">
            <div class="col-md-5"><strong>Motorista</strong><input type="text" class="form-control" value="{{ $recebimento->motorista }}" readonly></div>
            <div class="col-md-2"><strong>Hr. Janela</strong><input type="text" class="form-control" value="{{ $recebimento->horario_janela }}" readonly></div>
            <div class="col-md-2"><strong>Hr. Chegada</strong><input type="text" class="form-control" value="{{ $recebimento->horario_chegada }}" readonly></div>
          </div>

          <hr>
          <h6>SKUs da NF</h6>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>Data Conf</th>
                
                <th>SKU</th>
                <th>Qtd Conf</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($itens as $item)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                  
                  <td>{{ $item->sku }}</td>
                  <td>{{ $item->qtd_conferida ?? 0 }}</td>
                  <td>
                    @if($item->qtd_conferida == $item->quantidade)
                      <span class="badge bg-success">OK</span>
                    @else
                      <span class="badge bg-warning text-dark">DIVERGÊNCIA</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>

          <div class="mt-4">
            <strong>Foto Final - Veículo Descarregado</strong><br>
            <div class="mb-3">
                <!--<label for="foto_fim_veiculo" class="form-label">Foto Final - Veículo Descarregado</label>-->
                <input type="file" name="foto_fim_veiculo" id="foto_fim_veiculo" class="form-control" accept="image/*" required>
            </div>
          </div>

          <div class="mt-3">
            <strong>Assinatura Conferente TPC</strong><br>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="confirmacao" name="confirmacao" required>
              <label class="form-check-label" for="confirmacao">
                Declaro que conferi a carga conforme recebido fisicamente em doca, de acordo com as especificações e padrões da empresa.
              </label>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
          
    <!-- Campos + Tabela + Input da foto final + Checkbox + Botões -->
    <button type="submit" class="btn btn-success">Fechar Conferência</button>

        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal fade" id="modalConferirItem" tabindex="-1">
  <div class="modal-dialog">
    <form id="formConferirItem" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Conferir Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="item_id" id="item_id">
          <p><strong>SKU:</strong> <span id="skuModal"></span></p>
          <p><strong>Descrição:</strong> <span id="descModal"></span></p>

          <div class="mb-3">
            <label>Quantidade Conferida</label>
            <input type="number" name="qtd_conferida" class="form-control" required min="0">
          </div>

          <div class="mb-3">
            <label>Observação</label>
            <textarea name="observacao" class="form-control" rows="2"></textarea>
          </div>

          <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="avariado" id="avariadoCheck">
            <label class="form-check-label" for="avariadoCheck">Item Avariado</label>
          </div>

          <div class="mb-3">
            <label>Foto da Avaria (opcional)</label>
            <input type="file" name="foto_avaria" accept="image/*" class="form-control">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Salvar Conferência</button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>
<script>
document.querySelectorAll('.btnConferir').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        document.getElementById('item_id').value = id;
        document.getElementById('skuModal').innerText = this.dataset.sku;
        document.getElementById('descModal').innerText = this.dataset.desc;

        const modal = new bootstrap.Modal(document.getElementById('modalConferirItem'));
        modal.show();
    });
});

document.getElementById('formConferirItem').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const itemId = formData.get('item_id');

    fetch(`/setores/conferencia/item/${itemId}/conferir`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            alert(data.mensagem);
            location.reload();
        } else {
            alert('Erro: ' + (data.mensagem ?? 'Falha ao salvar.'));
        }
    })
    .catch(err => {
    console.error('Erro detalhado:', err);
    alert('Erro ao enviar conferência. Veja o console (F12) para detalhes.');
});
});
</script>

<script>
document.getElementById('btnFecharConferencia').addEventListener('click', function(e) {
    const divergentes = document.querySelectorAll('.badge.bg-warning');

    if (divergentes.length > 0) {
        e.preventDefault(); // interrompe envio do formulário

        if (confirm('A conferência tem divergências, deseja mesmo continuar?')) {
            this.closest('form').submit(); // envia o formulário manualmente
        }
    }
});
</script>

@endsection