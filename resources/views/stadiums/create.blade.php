
    <h1>Adicionar Estádio</h1>

    <form action="{{ route('stadiums.store') }}" method="POST">
        @csrf
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" required>

        <label for="location">Localização:</label>
        <input type="text" name="location" id="location" required>

        <label for="capacity">Capacidade:</label>
        <input type="number" name="capacity" id="capacity" required>

        <button type="submit">Criar Estádio</button>
    </form>
