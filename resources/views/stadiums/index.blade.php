@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Estádios</h3>
            <div class="card-tools">
                <a href="{{ route('stadiums.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Adicionar Estádio
                </a>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="stadiumsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Localização</th>
                        <th>Capacidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stadiums as $stadium)
                        <tr>
                            <td>{{ $stadium->id }}</td>
                            <td>{{ $stadium->name }}</td>
                            <td>{{ $stadium->location }}</td>
                            <td>{{ $stadium->capacity }}</td>
                            <td>
                                <a href="{{ route('stadiums.edit', $stadium->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('stadiums.destroy', $stadium->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja eliminar este estádio?')">
                                        <i class="fas fa-trash"></i> Eliminar
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
            <p class="mb-0">Total de estádios: {{ $stadiums->count() }}</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stadiumsTable').DataTable({
            responsive: true,
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese.json"
            }
        });
    });
</script>
@endpush



