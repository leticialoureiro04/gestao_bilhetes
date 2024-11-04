<div class="container">
    <h1>Lista de Bilhetes</h1>

    @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Emitir Novo Bilhete</a>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Jogo</th>
                <th>Assento</th>
                <th>Usuário</th>
                <th>Preço</th>
                <th>Status</th>
                @if(auth()->user()->hasRole('admin'))
                    <th>Ações</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->game->stadium->name }} - {{ $ticket->game->date_time }}</td>
                    <td>{{ $ticket->seat->id }}</td>
                    <td>{{ $ticket->user->name }}</td>
                    <td>{{ $ticket->price }}</td>
                    <td>{{ $ticket->status }}</td>
                    @if(auth()->user()->hasRole('admin'))
                        <td>
                            <!-- Botão Cancelar apenas para o admin -->
                            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Cancelar</button>
                            </form>
                            @if($ticket->status === 'disponível')
                                <form action="{{ route('tickets.sell', $ticket->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Vender</button>
                                </form>
                                <form action="{{ route('tickets.reserve', $ticket->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary">Reservar</button>
                                </form>
                            @endif
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
