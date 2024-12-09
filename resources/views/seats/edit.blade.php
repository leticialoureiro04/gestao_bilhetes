@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">{{ __('seats.edit_seat') }}</h3>
        </div>
        <form action="{{ route('seats.update', $seat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="stand_id">{{ __('seats.stand') }}</label>
                    <select class="form-control" id="stand_id" name="stand_id" required>
                        <option value="">{{ __('seats.select_stand') }}</option>
                        @foreach($stands as $stand)
                            <option value="{{ $stand->id }}" {{ $seat->stand_id == $stand->id ? 'selected' : '' }}>
                                {{ $stand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="row_number">{{ __('seats.row_number') }}</label>
                    <input type="number" class="form-control" id="row_number" name="row_number" value="{{ $seat->row_number }}" placeholder="{{ __('seats.row_number') }}" required>
                </div>

                <div class="form-group">
                    <label for="seat_number">{{ __('seats.seat_number') }}</label>
                    <input type="number" class="form-control" id="seat_number" name="seat_number" value="{{ $seat->seat_number }}" placeholder="{{ __('seats.seat_number') }}" required>
                </div>

                <div class="form-group">
                    <label for="seat_type_id">{{ __('seats.seat_type') }}</label>
                    <select class="form-control" id="seat_type_id" name="seat_type_id" required>
                        <option value="">{{ __('seats.select_seat_type') }}</option>
                        @foreach($seatTypes as $type)
                            <option value="{{ $type->id }}" {{ $seat->seat_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">{{ __('seats.status') }}</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="disponível" {{ $seat->status == 'disponível' ? 'selected' : '' }}>{{ __('seats.available') }}</option>
                        <option value="reservado" {{ $seat->status == 'reservado' ? 'selected' : '' }}>{{ __('seats.reserved') }}</option>
                        <option value="vendido" {{ $seat->status == 'vendido' ? 'selected' : '' }}>{{ __('seats.sold') }}</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('seats.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('seats.come_back') }}
                </a>
                <button type="submit" class="btn btn-warning">{{ __('seats.update_seat') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection



