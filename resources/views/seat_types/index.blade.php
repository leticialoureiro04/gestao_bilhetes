
    <div class="container">
        <h1>Lista de Tipos de Lugares</h1>

        <a href="{{ route('seat_types.create') }}" class="btn btn-primary mb-3">Adicionar Tipo de Lugar</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seatTypes as $seatType)
                    <tr>
                        <td>{{ $seatType->id }}</td>
                        <td>{{ $seatType->name }}</td>
                        <td>{{ $seatType->description }}</td>
                        <td>{{ $seatType->price }}</td>
                        <td>
                            <a href="{{ route('seat_types.edit', $seatType->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('seat_types.destroy', $seatType->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Apagar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
