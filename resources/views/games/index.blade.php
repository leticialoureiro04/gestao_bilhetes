@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Jogos</h3>
            <a href="{{ route('games.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Adicionar Novo Jogo
            </a>
        </div>
        <div class="card-body">
            <table id="gamesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estádio</th>
                        <th>Data e Hora</th>
                        <th>Equipa da Casa</th>
                        <th>Equipa Visitante</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                        <tr>
                            <td>{{ $game->id }}</td>
                            <td>{{ $game->stadium->name }}</td>
                            <td>{{ $game->date_time }}</td>
                            <td>{{ $game->teams->where('pivot.role', 'home')->first()?->name ?? 'N/A' }}</td>
                            <td>{{ $game->teams->where('pivot.role', 'away')->first()?->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">Total de jogos: {{ $games->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#gamesTable').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
            }
        });
    });
</script>
@endpush




