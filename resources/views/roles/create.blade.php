<!-- resources/views/roles/create.blade.php -->
<h1>Criar Novo Papel</h1>
<form action="{{ route('roles.store') }}" method="POST">
    @csrf
    <label for="name">Nome do Papel:</label>
    <input type="text" name="name" id="name" required>
    <button type="submit">Salvar</button>
</form>
