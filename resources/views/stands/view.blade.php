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
                        @php
                            // Determinar a classe do botão com base no status e tipo
                            $buttonClass = match($seat->status) {
                                'ocupado' => 'btn-danger', // Vermelho para ocupados
                                default => match($seat->seat_type_id) {
                                    1 => 'btn-warning', // Amarelo para VIPs
                                    2 => 'btn-success', // Verde para normais disponíveis
                                    default => 'btn-secondary', // Cinza para outros
                                },
                            };
                        @endphp

                        <!-- Renderizar o botão -->
                        <button
                            class="btn {{ $buttonClass }} btn-sm seat-button"
                            data-seat-id="{{ $seat->id }}"
                            data-status="{{ $seat->status }}"
                            data-seat-type="{{ $seat->seat_type_id }}">
                            {{ $seat->row_number }}-{{ $seat->seat_number }}
                        </button>
                    @endforeach
                </div>
            @endfor
        </div>

        <!-- Botão para confirmar compra -->
        <button id="confirmPurchase" class="btn btn-primary mt-3" disabled>Comprar Lugares</button>
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

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-warning {
        background-color: #ffc107;
        color: black;
    }
</style>
@endsection





