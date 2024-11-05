@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Equipas</h3>
            <a href="{{ route('teams.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Adicionar Equipa
            </a>
        </div>
        <div class="card-body">
            <table id="teamsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cidade</th>
                        <th>Fundação</th>
                        <th>Ações</th>
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
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja eliminar esta equipa?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">Total de equipas: {{ $teams->count() }}</p>
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
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
            }
        });
    });
</script>
@endpush


