@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Novo Equipamento</h1>
    <form action="{{ route('equipamentos.store') }}" method="POST">
        @csrf
        @include('equipamentos.form')
        <button type="submit" class="btn btn-primary mt-3">Salvar</button>
    </form>
</div>
@endsection
