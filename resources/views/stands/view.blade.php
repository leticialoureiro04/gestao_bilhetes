@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lugares da Bancada: {{ $stand->name }}</h1>
    <p>Linhas: {{ $stand->num_rows }}</p>
    <p>Assentos por linha: {{ $stand->seats_per_row }}</p>

    @if ($stand->seats->isEmpty())
        <p>Nenhum lugar disponível para esta bancada.</p>
    @else
        <div class="seats-layout">
            @for ($row = 1; $row <= $stand->num_rows; $row++)
                <div class="seat-row">
                    @foreach ($stand->seats->where('row_number', $row) as $seat)
                        <button class="btn btn-success btn-sm">
                            {{ $seat->row_number }}-{{ $seat->seat_number }}
                        </button>
                    @endforeach
                </div>
            @endfor
        </div>
    @endif
</div>

<style>
    .seats-layout {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .seat-row {
        display: flex;
        gap: 5px;
    }
</style>
@endsection




