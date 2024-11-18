@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Bilhetes</h3>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('tickets.create') }}" class="btn btn-primary float-right">
                    <i class="fas fa-plus"></i> Emitir Novo Bilhete
                </a>
            @endif
        </div>
        <div class="card-body">
            <table id="ticketsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Jogo</th>
                        <th>Assento</th>
                        <th>Utilizador</th>
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
                            <td>{{ chr(64 + $ticket->seat->row_number) }}-{{ $ticket->seat->seat_number }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>{{ $ticket->price }}</td>
                            <td>{{ $ticket->status }}</td>
                            @if(auth()->user()->hasRole('admin'))
                                <td>
                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja cancelar este bilhete?')">
                                            <i class="fas fa-trash"></i> Cancelar
                                        </button>
                                    </form>
                                    @if($ticket->status === 'disponível')
                                        <form action="{{ route('tickets.sell', $ticket->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-dollar-sign"></i> Vender
                                            </button>
                                        </form>
                                        <form action="{{ route('tickets.reserve', $ticket->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary">
                                                <i class="fas fa-bookmark"></i> Reservar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">Total de bilhetes: {{ $tickets->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush


