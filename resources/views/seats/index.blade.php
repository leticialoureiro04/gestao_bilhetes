@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Seats</h3>
            <!--<a href="{{ route('seats.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Add Seat
            </a>-->
        </div>
        <div class="card-body">
            <table id="seatsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Stadium</th>
                        <th>Stand</th>
                        <th>Line Number</th>
                        <th>Seat Number</th>
                        <th>Type of Seat</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seats as $seat)
                        <tr>
                            <td>{{ $seat->id }}</td>
                            <td>{{ $seat->stand->stadium->name ?? 'N/A' }}</td>
                            <td>{{ $seat->stand->name ?? 'Sem Bancada' }}</td>
                            <td>{{ chr(64 + $seat->row_number) }}</td> <!-- Número da Linha -->
                            <td>{{ $seat->seat_number ?? 'N/A' }}</td> <!-- Número do Assento -->
                            <td>{{ $seat->seatType->name ?? 'Sem Tipo' }}</td>
                            <td>{{ $seat->status ?? 'Sem Status' }}</td>
                            <td>
                                <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('seats.destroy', $seat->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this place?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">Total Seats: {{ $seats->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#seatsTable').DataTable({
            responsive: true,
            debug: true,
        });
    });
</script>
@endpush


