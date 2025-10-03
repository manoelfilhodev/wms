@extends($layout)

@section('content')
<div class="page-content">
    <div class="container">
        <h4 class="mb-4">Cadastrar Novo Usuário</h4>

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

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-user-line"></i></span>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Login</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-user-shared-line"></i></span>
                        <input type="text" name="login" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-lock-password-line"></i></span>
                        <input type="password" name="senha" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Unidade</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-building-line"></i></span>
                        <input type="text" name="unidade" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-toggle-line"></i></span>
                        <select name="status" class="form-select" required>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Código Nível</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-user-settings-line"></i></span>
                        <select name="cod_nivel" class="form-select" required>
                            <option value="1">Admin</option>
                            <option value="2">Expedição</option>
                            <option value="3">Separação</option>
                            <option value="4">Recebimento</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Descrição Nível</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                        <input type="text" name="desc_nivel" class="form-control" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">
                <i class="ri-check-line"></i> Cadastrar
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
