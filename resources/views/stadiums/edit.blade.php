
    <h1>Editar Estádio</h1>

    <form action="{{ route('stadiums.update', $stadium->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" value="{{ $stadium->name }}" required>

        <label for="location">Localização:</label>
        <input type="text" name="location" id="location" value="{{ $stadium->location }}" required>

        <label for="capacity">Capacidade:</label>
        <input type="number" name="capacity" id="capacity" value="{{ $stadium->capacity }}" required>

        <button type="submit">Atualizar Estádio</button>
    </form>
