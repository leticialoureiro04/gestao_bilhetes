@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('roles.list_of_roles') }}</h3>
            <a href="{{ route('roles.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> {{ __('roles.add_role') }}
            </a>
        </div>
        <div class="card-body">
            <table id="rolesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('roles.role_name') }}</th>
                        <th>{{ __('roles.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> {{ __('roles.edit_role') }}
                                </a>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('roles.confirm_delete') }}')">
                                        <i class="fas fa-trash"></i> {{ __('roles.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">{{ __('roles.total_roles') }}: {{ $roles->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#rolesTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush



