
@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-4">Foto Inicial do Veículo - Recebimento NF {{ $recebimento->nota_fiscal }}</h4>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('setores.conferencia.salvarFotoInicio', $recebimento->id) }}" method="POST" enctype="multipart/form-data" id="formFotoInicio">
        @csrf
        <div class="mb-3">
            <label for="foto_inicio_veiculo" class="form-label">Selecione a Foto do Início do Veículo</label>
            <input class="form-control" type="file" name="foto_inicio_veiculo" id="foto_inicio_veiculo" accept="image/*" required>
        </div>
        <div class="mb-3 text-center">
            <img id="previewFoto" src="#" alt="Prévia da Foto" style="display: none; max-width: 100%; height: auto; border: 1px solid #ccc; padding: 4px;">
        </div>

        <button type="submit" class="btn btn-success">Salvar Foto</button>
    </form>
</div>

{{-- Overlay loader --}}
<div id="overlayLoader" class="d-none">
    <div class="overlay-background"></div>
    <div class="overlay-content text-center">
        <div class="logo-loader">
            <img src="{{ asset('images/logo-sem-nome.png') }}" alt="Carregando..." class="systex-seta-gif">
        </div>
        <p class="text-light mt-3">Processando imagem...</p>
    </div>
</div>

<style>
#overlayLoader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1055;
}
.overlay-background {
    position: absolute;
    background: rgba(0, 0, 0, 0.85);
    width: 100%;
    height: 100%;
}
.overlay-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.logo-loader {
    position: relative;
    display: inline-block;
}
.systex-logo {
    width: 110px;
    display: block;
    margin: 0 auto;
}
.systex-seta-gif {
    width: 120px;
    display: block;
    margin: 10px auto 0 auto;
}
</style>

<script>
document.getElementById('formFotoInicio').addEventListener('submit', function () {
    document.getElementById('overlayLoader').classList.remove('d-none');
});

document.getElementById('foto_inicio_veiculo').addEventListener('change', function (e) {
    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.getElementById('previewFoto');
        preview.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(e.target.files[0]);
});
</script>
@endsection
