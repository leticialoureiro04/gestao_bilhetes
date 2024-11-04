<div class="container">
    <h1>Editar Bilhete</h1>

    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="game_id" class="form-label">Jogo</label>
            <select name="game_id" id="game_id" class="form-control" required>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" {{ $game->id == $ticket->game_id ? 'selected' : '' }}>
                        {{ $game->stadium->name }} - {{ $game->date_time }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="seat_id" class="form-label">Assento</label>
            <select name="seat_id" id="seat_id" class="form-control" required>
                @foreach($seats as $seat)
                    <option value="{{ $seat->id }}" {{ $seat->id == $ticket->seat_id ? 'selected' : '' }}>
                        Assento {{ $seat->id }} - Planta: {{ $seat->stadiumPlan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Usuário</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $ticket->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Preço</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $ticket->price }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control">
                <option value="disponível" {{ $ticket->status == 'disponível' ? 'selected' : '' }}>Disponível</option>
                <option value="vendido" {{ $ticket->status == 'vendido' ? 'selected' : '' }}>Vendido</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Bilhete</button>
    </form>
</div>
