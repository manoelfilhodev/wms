@extends($layout)

@section('content')
<div class="page-content">
    <div class="container">
        <h4 class="mb-4">Editar Usuário</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Erro!</strong> Verifique os campos abaixo:<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('usuarios.update', $usuario->id_user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-user-line"></i></span>
                        <input type="text" name="nome" class="form-control" value="{{ $usuario->nome }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Login</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-user-shared-line"></i></span>
                        <input type="text" name="login" class="form-control" value="{{ $usuario->email }}" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Unidade</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-building-line"></i></span>
                        <input type="text" name="unidade" class="form-control" value="{{ $usuario->unidade_id }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-toggle-line"></i></span>
                        <select name="status" class="form-select" required>
                            <option value="1" {{ $usuario->status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ $usuario->status == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                

                <div class="col-md-3">
                    <label class="form-label">Descrição Nível</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                        <input type="text" name="tipo" class="form-control" value="{{ $usuario->tipo }}" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                <i class="ri-save-line"></i> Atualizar
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('input[required], select[required]');
        
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                if (input.value.trim() !== '') {
                    input.classList.remove('is-invalid');
                }
            });
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            let valid = true;

            inputs.forEach(input => {
                if (input.value.trim() === '') {
                    input.classList.add('is-invalid');
                    valid = false;
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
