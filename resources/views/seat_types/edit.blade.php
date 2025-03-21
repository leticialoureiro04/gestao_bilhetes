@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">{{ __('seat_types.edit_seat_type') }}</h3>
        </div>
        <form action="{{ route('seat_types.update', $seatType->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="name">{{ __('seat_types.seat_type_name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $seatType->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('seat_types.description') }}</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $seatType->description }}</textarea>
                </div>
                <div class="form-group">
                    <label for="price">{{ __('seat_types.price') }}</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $seatType->price }}" required>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('seat_types.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('seat_types.come_back') }}
                </a>
                <button type="submit" class="btn btn-warning">{{ __('seat_types.update') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


