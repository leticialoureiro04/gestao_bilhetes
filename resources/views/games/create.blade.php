@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Criar Novo Jogo</h3>
        </div>
        <form action="{{ route('games.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="stadium_id">Estádio</label>
                    <select name="stadium_id" id="stadium_id" class="form-control" required>
                        @foreach($stadiums as $stadium)
                            <option value="{{ $stadium->id }}">{{ $stadium->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_time">Data e Hora</label>
                    <input type="datetime-local" class="form-control" id="date_time" name="date_time" required>
                </div>
                <div class="form-group">
                    <label for="home_team">Equipa da Casa</label>
                    <select name="teams[home]" id="home_team" class="form-control" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="away_team">Equipa Visitante</label>
                    <select name="teams[away]" id="away_team" class="form-control" required>
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('games.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">
                    Criar Jogo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

