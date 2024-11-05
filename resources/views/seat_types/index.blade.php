@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Tipos de Lugares</h3>
            <a href="{{ route('seat_types.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Adicionar Tipo de Lugar
            </a>
        </div>
        <div class="card-body">
            <table id="seatTypesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Ações</th>
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
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('seat_types.destroy', $seatType->id) }}" method="POST" style="display:inline;">
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
                <p class="text-muted">Total de tipos de lugares: {{ $seatTypes->count() }}</p>
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
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
            }
        });
    });
</script>
@endpush


