<div class="container">
    <h1>Editar Equipa</h1>

    <form action="{{ route('teams.update', $team->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ $team->name }}" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Cidade</label>
            <input type="text" name="city" class="form-control" value="{{ $team->city }}">
        </div>
        <div class="mb-3">
            <label for="founded" class="form-label">Data de Fundação</label>
            <input type="date" name="founded" class="form-control" value="{{ $team->founded }}">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
