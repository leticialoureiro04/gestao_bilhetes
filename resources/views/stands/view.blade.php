@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ __('stands.stand_seats') }}: {{ $stand->name }}</h1>
    <p>{{ __('stands.lines') }}: {{ $stand->num_rows }}</p>
    <p>{{ __('stands.seats_per_row') }}: {{ $stand->seats_per_row }}</p>

    @if ($stand->seats->isEmpty())
        <p>{{ __('stands.no_space') }}</p>
    @else
        <div class="seats-layout">
            @for ($row = 1; $row <= $stand->num_rows; $row++)
                <div class="seat-row">
                    @foreach ($stand->seats->where('row_number', $row) as $seat)
                        @php
                            $buttonClass = match($seat->status) {
                                'vendido' => 'btn-danger',
                                default => match($seat->seat_type_id) {
                                    1 => 'btn-warning',
                                    2 => 'btn-success',
                                    default => 'btn-secondary',
                                },
                            };
                            $rowLetter = chr(64 + $row);
                        @endphp

                        <button
                            class="btn {{ $buttonClass }} btn-sm seat-button"
                            data-seat-id="{{ $seat->id }}"
                            data-status="{{ $seat->status }}"
                            data-seat-type="{{ $seat->seat_type_id }}">
                            {{ $rowLetter }}-{{ $seat->seat_number }}
                        </button>
                    @endforeach
                </div>
            @endfor
        </div>

        <form id="purchaseForm" method="POST" action="{{ route('tickets.buy') }}">
            @csrf
            <input type="hidden" name="seat_ids" id="selectedSeats" value="">
            <button id="confirmPurchase" class="btn btn-primary mt-3" disabled>{{ __('stands.buy_seats') }}</button>
        </form>
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

    .btn-selected {
        background-color: #007bff; /* Azul para lugares selecionados */
        color: white;
    }
</style>
@endsection

@push('scripts')
<script>
let selectedSeats = [];

document.querySelectorAll('.seat-button').forEach(button => {
    button.addEventListener('click', function () {
        const seatId = this.getAttribute('data-seat-id');

        // Alternar seleção
        if (selectedSeats.includes(seatId)) {
            // Remover da seleção
            selectedSeats = selectedSeats.filter(id => id !== seatId);
            this.classList.remove('btn-selected');
        } else {
            // Adicionar à seleção
            selectedSeats.push(seatId);
            this.classList.add('btn-selected');
        }

        // Atualizar o estado do botão de compra
        document.getElementById('confirmPurchase').disabled = selectedSeats.length === 0;

        // Atualizar o campo hidden com os IDs dos assentos selecionados
        document.getElementById('selectedSeats').value = JSON.stringify(selectedSeats);
    });
});

</script>
@endpush






