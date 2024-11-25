@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="max-width: 90%; margin: auto;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">Games List</h3>
            <a href="{{ route('games.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Game
            </a>
        </div>
        <div class="card-body">
            <table id="gamesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Stadium</th>
                        <th>Date and Time</th>
                        <th>Home Team</th>
                        <th>Visiting Team</th>
                        <th class="text-center">Actions</th>
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
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning btn-sm mr-2">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm mr-2">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    <a href="{{ route('stadiums.viewLayout', $game->stadium->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Stadium
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">Total Games: {{ $games->count() }}</p>
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
        });
    });
</script>
@endpush







