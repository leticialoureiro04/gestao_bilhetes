@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Seat Types</h3>
            <a href="{{ route('seat_types.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Add Seat Type
            </a>
        </div>
        <div class="card-body">
            <table id="seatTypesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seatTypes as $seatType)
                        <tr>
                            <td>{{ $seatType->id }}</td>
                            <td>{{ $seatType->name }}</td>
                            <td>{{ $seatType->description }}</td>
                            <td>{{ $seatType->price }}</td>
                            <td>
                                <a href="{{ route('seat_types.edit', $seatType->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('seat_types.destroy', $seatType->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">Total Seats Types: {{ $seatTypes->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#seatTypesTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush


