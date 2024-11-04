<div class="container">
    <h1>Lista de Jogos</h1>

    <a href="{{ route('games.create') }}" class="btn btn-primary mb-3">Adicionar Novo Jogo</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Estádio</th>
                <th>Data e Hora</th>
                <th>Equipa da Casa</th>
                <th>Equipa Visitante</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($games as $game)
                <tr>
                    <td>{{ $game->id }}</td>
                    <td>{{ $game->stadium->name }}</td>
                    <td>{{ $game->date_time }}</td>
                    <td>{{ $game->teams->where('pivot.role', 'home')->first()?->name ?? 'N/A' }}</td>
                    <td>{{ $game->teams->where('pivot.role', 'away')->first()?->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
