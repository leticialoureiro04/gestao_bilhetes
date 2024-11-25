@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Stadiums</h3>
            <div class="card-tools">
                <a href="{{ route('stadiums.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Stadium
                </a>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="stadiumsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Number of Stands</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stadiums as $stadium)
                        <tr>
                            <td>{{ $stadium->name }}</td>
                            <td>{{ $stadium->location }}</td>
                            <td>{{ $stadium->capacity }}</td>
                            <td>{{ $stadium->num_stands }}</td>
                            <td>
                                <a href="{{ route('stands.show', $stadium->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View Stands
                                </a>
                                <a href="{{ route('stadiums.viewLayout', $stadium->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i>
                                    View Stadiums
                                </a>
                                <a href="{{ route('stadiums.edit', $stadium->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('stadiums.destroy', $stadium->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this stadium?')">
                                        <i class="fas fa-trash"></i> Delete
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
            <p class="mb-0">Total Stadiums: {{ $stadiums->count() }}</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stadiumsTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush




