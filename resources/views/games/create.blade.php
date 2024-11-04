<div class="container">
    <h1>Criar Novo Jogo</h1>

    <form action="{{ route('games.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="stadium_id" class="form-label">Estádio</label>
            <select name="stadium_id" id="stadium_id" class="form-control" required>
                @foreach($stadiums as $stadium)
                    <option value="{{ $stadium->id }}">{{ $stadium->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="date_time" class="form-label">Data e Hora</label>
            <input type="datetime-local" class="form-control" id="date_time" name="date_time" required>
        </div>

        <div class="mb-3">
            <label for="home_team" class="form-label">Equipa da Casa</label>
            <select name="teams[home]" id="home_team" class="form-control" required>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="away_team" class="form-label">Equipa Visitante</label>
            <select name="teams[away]" id="away_team" class="form-control" required>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Criar Jogo</button>
    </form>
</div>
