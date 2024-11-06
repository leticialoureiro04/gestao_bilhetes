@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Lugares</h3>
            <a href="{{ route('seats.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Adicionar Lugar
            </a>
        </div>
        <div class="card-body">
            <table id="seatsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estádio</th>
                        <th>Planta do Estádio</th>
                        <th>Tipo de Lugar</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seats as $seat)
                        <tr>
                            <td>{{ $seat->id }}</td>
                            <td>{{ $seat->stadiumPlan->stadium->name }}</td>
                            <td>{{ $seat->stadiumPlan->name }}</td>
                            <td>{{ $seat->seatType->name }}</td>
                            <td>{{ $seat->status }}</td>
                            <td>
                                <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('seats.destroy', $seat->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">Total de lugares: {{ $seats->count() }}</p>
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
        });
    });
</script>
@endpush

