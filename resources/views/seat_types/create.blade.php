@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('seat_types.add_seat_type') }}</h3>
        </div>
        <form action="{{ route('seat_types.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">{{ __('seat_types.seat_type_name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">{{ __('seat_types.description') }}</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="price">{{ __('seat_types.price') }}</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('seat_types.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('seat_types.come_back') }}
                </a>
                <button type="submit" class="btn btn-primary">{{ __('seat_types.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


