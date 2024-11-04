<h1>Atribuir Papel a {{ $user->name }}</h1>
<form action="{{ route('users.assign_role.store', $user->id) }}" method="POST">
    @csrf
    <label for="role">Selecione o Papel:</label>
    <select name="role" id="role">
        @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
    <button type="submit">Atribuir Papel</button>
</form>
