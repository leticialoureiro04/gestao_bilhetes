<div class="container">
    <h1>Lista de Lugares</h1>

    <a href="{{ route('seats.create') }}" class="btn btn-primary mb-3">Adicionar Lugar</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Estádio</th>
                <th>Planta do Estádio</th>
                <th>Tipo de Lugar</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($seats as $seat)
                <tr>
                    <td>{{ $seat->id }}</td>
                    <td>{{ $seat->stadiumPlan->stadium->name }}</td>
                    <td>{{ $seat->stadiumPlan->name }}</td>
                    <td>{{ $seat->seatType->name }}</td>
                    <td>{{ $seat->status }}</td>
                    <td>
                        <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('seats.destroy', $seat->id) }}" method="POST" style="display:inline;">
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
