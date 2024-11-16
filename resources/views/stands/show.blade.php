@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bancadas do Estádio: {{ $stadium->name }}</h2>

    @foreach($stadium->stands as $stand)
        <div class="card my-4">
            <div class="card-header">
                <h4>Bancada: {{ $stand->name }}</h4>
            </div>
            <div class="card-body">
                <p><strong>Número de Filas:</strong> {{ $stand->num_rows }}</p>
                <p><strong>Lugares por Fila:</strong> {{ $stand->seats_per_row }}</p>
                <p><strong>Total de Assentos:</strong> {{ $stand->num_rows * $stand->seats_per_row }}</p>
            </div>
        </div>
    @endforeach

    <a href="{{ route('stadiums.index') }}" class="btn btn-secondary">Voltar à Lista de Estádios</a>
</div>
@endsection

