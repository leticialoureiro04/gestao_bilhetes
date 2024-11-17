@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Visualização do Estádio: {{ $stadium->name }}</h1>
    <div class="seat-layout">
        @foreach ($stadium->stands as $stand)
            <div class="stand">
                <h3>{{ $stand->name }}</h3>
                <p>Linhas: {{ $stand->num_rows }}</p>
                <p>Assentos por linha: {{ $stand->seats_per_row }}</p>
            </div>
        @endforeach
    </div>
</div>

<style>
    .seat-layout {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-top: 20px;
    }
    .stand {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        padding: 20px;
        text-align: center;
        font-weight: bold;
    }
</style>
@endsection


