@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Utilizadores</h3>
            <div class="card-tools">
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">Adicionar Utilizador</a>
            </div>
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Papel</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->roles->isNotEmpty())
                                    {{ $user->roles->pluck('name')->join(', ') }}
                                @else
                                    <span class="badge badge-secondary">Sem papel</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm mr-1">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="{{ route('users.assign_role', $user->id) }}" class="btn btn-primary btn-sm mr-1">
                                    <i class="fas fa-user-tag"></i> Atribuir Papel
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja eliminar este utilizador?')">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <p class="text-muted">Total de utilizadores: {{ $users->count() }}</p>
        </div>
        <!-- /.card-footer -->
    </div>
    <!-- /.card -->
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true,
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
            }
        });
    });
</script>
@endpush






