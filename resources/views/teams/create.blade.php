<div class="container">
    <h1>Adicionar Equipa</h1>

    <form action="{{ route('teams.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="city" class="form-label">Cidade</label>
            <input type="text" name="city" class="form-control">
        </div>
        <div class="mb-3">
            <label for="founded" class="form-label">Data de Fundação</label>
            <input type="date" name="founded" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
