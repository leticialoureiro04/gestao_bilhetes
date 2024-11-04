<!-- resources/views/roles/edit.blade.php -->
<h1>Editar Papel</h1>
<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="name">Nome do Papel:</label>
    <input type="text" name="name" id="name" value="{{ $role->name }}" required>
    <button type="submit">Atualizar</button>
</form>
