@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">{{ __('games.edit') }}</h3>
        </div>
        <form action="{{ route('games.update', $game->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="stadium_id">{{ __('games.stadium') }}</label>
                    <select name="stadium_id" id="stadium_id" class="form-control" required>
                        @foreach($stadiums as $stadium)
                            <option value="{{ $stadium->id }}" {{ $stadium->id == $game->stadium_id ? 'selected' : '' }}>
                                {{ $stadium->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_time">{{ __('games.date_time') }}</label>
                    <input type="datetime-local" class="form-control" id="date_time" name="date_time" value="{{ $game->date_time }}" required>
                </div>
                <div class="form-group">
                    <label for="home_team">{{ __('games.home_team') }}</label>
                    <select name="teams[home]" id="home_team" class="form-control" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->teams->where('pivot.role', 'home')->first()?->id == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="away_team">{{ __('games.visiting_team') }}</label>
                    <select name="teams[away]" id="away_team" class="form-control" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->teams->where('pivot.role', 'away')->first()?->id == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('games.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('games.back') }}
                </a>
                <button type="submit" class="btn btn-warning">
                    {{ __('games.update') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

