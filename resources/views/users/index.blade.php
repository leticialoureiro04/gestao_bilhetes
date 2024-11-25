@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Users</h3>
            <div class="card-tools">
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">Add User</a>
            </div>
        </div>
        <!-- /.card-header -->

        <div class="card-body">
            <table id="myTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Paper</th>
                        <th>Actions</th>
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
                                    <span class="badge badge-secondary">Paperless</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm mr-1">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('users.assign_role', $user->id) }}" class="btn btn-primary btn-sm mr-1">
                                    <i class="fas fa-user-tag"></i> Assign role
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash-alt"></i> Delete
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
            <p class="text-muted">Total of users: {{ $users->count() }}</p>
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
        });
    });
</script>
@endpush






