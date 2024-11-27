@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('tickets.issue_ticket') }}</h3>
        </div>
        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="game_id">{{ __('tickets.game') }}</label>
                    <select name="game_id" id="game_id" class="form-control" required>
                        <option value="">{{ __('tickets.select_game') }}</option>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}" data-stadium="{{ $game->stadium->name }}" data-stadium-id="{{ $game->stadium_id }}">
                                {{ $game->date_time }} - {{ $game->stadium->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="stadium">{{ __('tickets.stadium') }}</label>
                    <input type="text" id="stadium" name="stadium" class="form-control" readonly>
                    <input type="hidden" name="stadium_id" id="stadium_id">
                </div>
                <div class="form-group">
                    <label for="stadium_plan_id">{{ __('tickets.stadium_plan') }}</label>
                    <select name="stadium_plan_id" id="stadium_plan_id" class="form-control" required>
                        <option value="">{{ __('tickets.select_stadium_plan') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="seat_type_id">{{ __('tickets.type_of_place') }}</label>
                    <select name="seat_type_id" id="seat_type_id" class="form-control" required>
                        <option value="">{{ __('tickets.select_type_of_place') }}</option>
                        @foreach($seatTypes as $seatType)
                            <option value="{{ $seatType->id }}" data-price="{{ $seatType->price }}">{{ $seatType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="seat_id">{{ __('tickets.place') }}</label>
                    <select name="seat_id" id="seat_id" class="form-control" required>
                        <option value="">{{ __('tickets.select_place') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">{{ __('tickets.price') }}</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" readonly>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('tickets.back') }}
                </a>
                <button type="submit" class="btn btn-primary">{{ __('tickets.issue_ticket') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection







