<div class="container">
    <h1>Lista de Equipas</h1>

    <a href="{{ route('teams.create') }}" class="btn btn-primary mb-3">Adicionar Equipa</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cidade</th>
                <th>Fundação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $team)
                <tr>
                    <td>{{ $team->id }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->city }}</td>
                    <td>{{ $team->founded }}</td>
                    <td>
                        <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('teams.destroy', $team->id) }}" method="POST" style="display:inline;">
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
