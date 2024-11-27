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

        <form id="purchaseForm">
            @csrf
            <input type="hidden" name="seat_ids" id="selectedSeats" value="">
            <button id="confirmPurchase" class="btn btn-primary mt-3" disabled>{{ __('stands.buy_seats') }}</button>
        </form>
    @endif
</div>
@endsection








