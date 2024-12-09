@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="max-width: 90%; margin: auto;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0">{{ __('games.list') }}</h3>
            @if(auth()->user()->hasRole('admin')) <!-- Apenas para administradores -->
                <a href="{{ route('games.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('games.add') }}
                </a>
            @endif
        </div>
        <div class="card-body">
            <table id="gamesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('games.id') }}</th>
                        <th>{{ __('games.stadium') }}</th>
                        <th>{{ __('games.date_time') }}</th>
                        <th>{{ __('games.home_team') }}</th>
                        <th>{{ __('games.visiting_team') }}</th>
                        <th class="text-center">{{ __('games.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                        <tr>
                            <td>{{ $game->id }}</td>
                            <td>{{ $game->stadium->name }}</td>
                            <td>{{ $game->date_time }}</td>
                            <td>{{ $game->teams->where('pivot.role', 'home')->first()?->name ?? __('games.no_info') }}</td>
                            <td>{{ $game->teams->where('pivot.role', 'away')->first()?->name ?? __('games.no_info') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    @if(auth()->user()->hasRole('admin')) <!-- Ações específicas para administradores -->
                                        <a href="{{ route('games.edit', $game->id) }}" class="btn btn-warning btn-sm mr-2">
                                            <i class="fas fa-edit"></i> {{ __('games.edit') }}
                                        </a>
                                        <form action="{{ route('games.destroy', $game->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mr-2">
                                                <i class="fas fa-trash"></i> {{ __('games.delete') }}
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Mostrar o botão "View Stadium" apenas para jogos futuros -->
                                    @if (\Carbon\Carbon::now()->lessThanOrEqualTo(\Carbon\Carbon::parse($game->date_time)))
                                        <a href="{{ route('stadiums.viewLayout', $game->stadium->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> {{ __('games.view_stadium') }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">{{ __('games.total') }}: {{ $games->count() }}</p>
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










