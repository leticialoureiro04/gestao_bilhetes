@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('teams.team_list') }}</h3>
            <a href="{{ route('teams.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> {{ __('teams.add_team') }}
            </a>
        </div>
        <div class="card-body">
            <table id="teamsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('teams.id') }}</th>
                        <th>{{ __('teams.name') }}</th>
                        <th>{{ __('teams.city') }}</th>
                        <th>{{ __('teams.founded') }}</th>
                        <th>{{ __('teams.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{ $team->id }}</td>
                            <td>{{ $team->name }}</td>
                            <td>{{ $team->city }}</td>
                            <td>{{ $team->founded }}</td>
                            <td>
                                <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('teams.edit') }}
                                </a>
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('teams.confirm_delete') }}')">
                                        <i class="fas fa-trash"></i> {{ __('teams.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">{{ __('teams.total_teams') }}: {{ $teams->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#teamsTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush



