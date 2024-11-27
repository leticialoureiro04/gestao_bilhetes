@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('teams.add_team') }}</h3>
        </div>
        <form action="{{ route('teams.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">{{ __('teams.name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="city">{{ __('teams.city') }}</label>
                    <input type="text" class="form-control" id="city" name="city">
                </div>
                <div class="form-group">
                    <label for="founded">{{ __('teams.founding_date') }}</label>
                    <input type="date" class="form-control" id="founded" name="founded">
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('teams.back') }}
                </a>
                <button type="submit" class="btn btn-primary">{{ __('teams.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


