@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">{{ __('stadiums.edit_stadium') }}</h3>
        </div>
        <form action="{{ route('stadiums.update', $stadium->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="name">{{ __('stadiums.name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $stadium->name }}" required>
                </div>
                <div class="form-group">
                    <label for="location">{{ __('stadiums.location') }}</label>
                    <input type="text" class="form-control" name="location" id="location" value="{{ $stadium->location }}" required>
                </div>
                <div class="form-group">
                    <label for="capacity">{{ __('stadiums.capacity') }}</label>
                    <input type="number" class="form-control" name="capacity" id="capacity" value="{{ $stadium->capacity }}" required>
                </div>
                <div class="form-group">
                    <label for="num_stands">{{ __('stadiums.num_stands') }}</label>
                    <input type="number" class="form-control" name="num_stands" id="num_stands" value="{{ $stadium->num_stands }}" min="1" max="4" required>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('stadiums.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('stadiums.come_back') }}
                </a>
                <button type="submit" class="btn btn-warning">{{ __('stadiums.update_stadium') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


