<h1>Editar Utilizador</h1>

<form action="{{ route('users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="name">Nome:</label>
    <input type="text" name="name" id="name" value="{{ $user->name }}" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" value="{{ $user->email }}" required>

    <button type="submit">Atualizar Utilizador</button>
</form>
