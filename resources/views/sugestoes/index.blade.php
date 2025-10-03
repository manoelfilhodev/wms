@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="mb-3">Histórico e Sugestões de Atualizações</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('sugestoes.store') }}" method="POST" class="mb-4">
        @csrf
        <input type="text" name="titulo" placeholder="Título da sugestão" class="form-control mb-2" required>
        <textarea name="descricao" placeholder="Descreva a sugestão" rows="3" class="form-control mb-2" required></textarea>
        <button class="btn btn-primary">Enviar Sugestão</button>
    </form>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Usuário</th>
                <th>Data</th>
                <th>Resposta</th>
            </tr>
        </thead>
        <tbody>
@foreach($sugestoes as $s)
    <tr>
        <td>{{ $s->titulo }}</td>
        <td>
            <span class="badge bg-{{ $s->status === 'concluida' ? 'success' : ($s->status === 'recusada' ? 'danger' : ($s->status === 'em_andamento' ? 'info' : 'warning')) }}">
                {{ ucfirst($s->status) }}
            </span>
        </td>
        <td>{{ $s->usuario->nome ?? '-' }}</td>
        <td>{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y H:i') }}</td>
        <td>
            @if(Auth::user()->tipo === 'admin' || Auth::user()->tipo === 'gestor')
                <form action="{{ route('sugestoes.update', $s->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <select name="status" class="form-select mb-1" required>
                        <option value="pendente" {{ $s->status === 'pendente' ? 'selected' : '' }}>Pendente</option>
                        <option value="em_andamento" {{ $s->status === 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                        <option value="concluida" {{ $s->status === 'concluida' ? 'selected' : '' }}>Concluída</option>
                        <option value="recusada" {{ $s->status === 'recusada' ? 'selected' : '' }}>Recusada</option>
                    </select>
                    <textarea name="resposta" class="form-control mb-1" placeholder="Informe o retorno ao usuário">{{ $s->resposta }}</textarea>
                    <button class="btn btn-sm btn-success">Atualizar</button>
                </form>
            @else
                {{ $s->resposta ?? '-' }}
            @endif
            @if($s->respostas->count())
                <hr>
                <small class="text-muted d-block mb-1">Histórico:</small>
                @foreach($s->respostas as $r)
                    <div class="mb-1 p-2 bg-light rounded">
                        <strong>{{ ucfirst($r->status) }}</strong> em {{ \Carbon\Carbon::parse($r->created_at)->format('d/m/Y H:i') }}<br>
                        <em>{{ $r->resposta }}</em><br>
                        <small>Por: {{ $r->autor->nome ?? 'Desconhecido' }}</small>
                    </div>
                @endforeach
            @endif
        </td>
        
    </tr>
@endforeach
</tbody>
    </table>
</div>
@endsection
