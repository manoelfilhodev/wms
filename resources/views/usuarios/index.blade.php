@extends($layout)

@section('content')
<div class="page-content">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Lista de Usuários</h4>
            <a href="{{ route('usuarios.create') }}" class="btn btn-success">
    <i class="mdi mdi-account-plus-outline me-1"></i> Novo Usuário
</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Login</th>
                                <th>Unidade</th>
                                <th>Status</th>
                                <th>Nível</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->nome }}</td>
                                    <td>{{ mb_strtolower($usuario->email) }}</td>
                                    <td>{{ $usuario->unidade_id }}</td>
                                    <td>
                                        @if($usuario->status == 'ativo')
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-danger">Inativo</span>
                                        @endif
                                    </td>
                                    <td>{{ $usuario->tipo }}</td>
                                    <td>
    <a href="{{ route('usuarios.edit', $usuario->id_user) }}" class="btn btn-icon btn-sm btn-primary me-1" title="Editar">
        <i class="mdi mdi-pencil-outline"></i>
    </a>

    <form action="{{ route('usuarios.destroy', $usuario->id_user) }}" method="POST" class="d-inline" onsubmit="return confirm('Deseja realmente excluir este usuário?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-icon btn-sm btn-danger" title="Excluir">
            <i class="mdi mdi-trash-can-outline"></i>
        </button>
    </form>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum usuário encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
