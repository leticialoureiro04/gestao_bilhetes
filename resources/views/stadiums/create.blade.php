@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('stadiums.add_stadium') }}</h3>
        </div>
        <form action="{{ route('stadiums.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">{{ __('stadiums.name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('stadiums.name') }}" required>
                </div>
                <div class="form-group">
                    <label for="location">{{ __('stadiums.location') }}</label>
                    <input type="text" class="form-control" name="location" id="location" placeholder="{{ __('stadiums.location') }}" required>
                </div>
                <div class="form-group">
                    <label for="capacity">{{ __('stadiums.capacity') }}</label>
                    <input type="number" class="form-control" name="capacity" id="capacity" placeholder="{{ __('stadiums.capacity') }}" required>
                </div>
                <div class="form-group">
                    <label for="num_stands">{{ __('stadiums.num_stands') }}</label>
                    <input type="number" class="form-control" name="num_stands" id="num_stands" min="1" max="4" required>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('stadiums.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('stadiums.come_back') }}
                </a>
                <button type="submit" class="btn btn-primary">{{ __('stadiums.create_stadium') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


