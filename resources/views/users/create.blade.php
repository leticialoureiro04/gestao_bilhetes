<h1>Adicionar Utilizador</h1>

<form action="{{ route('users.store') }}" method="POST">
    @csrf
    <label for="name">Nome:</label>
    <input type="text" name="name" id="name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>

    <button type="submit">Criar Utilizador</button>
</form>
