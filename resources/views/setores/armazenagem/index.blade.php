@extends($layout)

@section('content')
<div class="container">
   @include('partials.breadcrumb-auto')
   <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Armazenagem de Produto</h4>
    @auth
                    @if(Auth::user()->tipo === 'admin')
    <a href="{{ route('relatorios.armazenagem') }}" class="btn btn-outline-primary btn-sm">
        <i class="mdi mdi-chart-bar"></i> Relatório
    </a>
    
     @endif
                @endauth
</div>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <form method="POST" action="{{ route('armazenagem.store') }}">
        @csrf
        <div class="row mb-2">
            <div class="col-12 mb-3">
                <input type="text" autocapitalize="off" name="sku" id="sku" class="form-control text-uppercase" placeholder="Código do Produto" autocomplete="off" required>
                <small id="descricao" class="form-text text-muted mt-1" style="font-weight: bold; text-transform: uppercase;"></small>
                <small id="sku-error" class="text-danger d-none">Produto não encontrado no sistema.</small>
            </div>
            <div class="col-12 mb-3">
                <input type="tel" name="quantidade" class="form-control" placeholder="QTD A ARMAZENAR" required>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12 mb-3">
    <input type="text" autocapitalize="off" name="endereco" id="endereco" class="form-control text-uppercase" placeholder="Endereço de Destino" autocomplete="off" required>
    <small id="posicao-info" class="form-text text-muted mt-1" style="font-weight: bold; text-transform: uppercase;"></small>
    <small id="posicao-error" class="text-danger d-none">Posição não encontrada no sistema.</small>
</div>

        </div>
        <div class="mb-3">
            <textarea name="observacoes" class="form-control text-uppercase" placeholder="Observações"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Armazenar</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let skusValidos = [];

$(document).ready(function () {
    $('#sku').on('input', function () {
        const input = $(this).val();

        if (input.length >= 2) {
            $.get("{{ route('armazenagem.buscarSkus') }}", { term: input }, function (data) {
                skusValidos = data;

                if (data.includes(input)) {
                    $('#sku-error').addClass('d-none');
                    $('button[type=\"submit\"]').prop('disabled', false);
                } else {
                    $('#sku-error').removeClass('d-none');
                    $('button[type=\"submit\"]').prop('disabled', true);
                }
            });
            
            $.get("{{ route('armazenagem.buscarDescricao') }}", { sku: input }, function (data) {
    $('#descricao').text(data.descricao.toUpperCase());
}).fail(function () {
    $('#descricao').text('');
});
        } else {
            $('#sku-error').addClass('d-none');
            $('button[type=\"submit\"]').prop('disabled', false);
        }
    });
});

let posicoesValidas = [];

$('#endereco').on('input', function () {
    const input = $(this).val();

    if (input.length >= 2) {
        $.get("{{ route('armazenagem.buscarPosicoes') }}", { term: input }, function (data) {
            posicoesValidas = data;

            if (data.includes(input)) {
                $('#posicao-error').addClass('d-none');
                $('#posicao-info').text('✅ Posição válida.').css('color', 'green');
                $('button[type="submit"]').prop('disabled', false);
            } else {
                $('#posicao-error').removeClass('d-none');
                $('#posicao-info').text('❌ Posição não encontrada.').css('color', 'red');
                $('button[type="submit"]').prop('disabled', true);
            }
        });
    } else {
        $('#posicao-error').addClass('d-none');
        $('#posicao-info').text('');
        $('button[type="submit"]').prop('disabled', false);
    }
});


</script>

    
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alert = document.querySelector('.alert-success');
        if (alert) {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = 0;
                setTimeout(() => alert.remove(), 500); // remove do DOM após o fade
            }, 3000);
        }
    });
</script>

@endsection
