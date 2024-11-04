
    <h1>Lista de Estádios</h1>

    <a href="{{ route('stadiums.create') }}">Adicionar Estádio</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Localização</th>
                <th>Capacidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stadiums as $stadium)
                <tr>
                    <td>{{ $stadium->id }}</td>
                    <td>{{ $stadium->name }}</td>
                    <td>{{ $stadium->location }}</td>
                    <td>{{ $stadium->capacity }}</td>
                    <td>
                        <a href="{{ route('stadiums.edit', $stadium->id) }}">Editar</a>
                        <form action="{{ route('stadiums.destroy', $stadium->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
