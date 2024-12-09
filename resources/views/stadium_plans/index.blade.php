@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Plantas de Estádio</h3>
            <a href="{{ route('stadium_plans.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Adicionar Planta de Estádio
            </a>
        </div>
        <div class="card-body">
            <table id="stadiumPlansTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome da Planta</th>
                        <th>Estádio</th>
                        <th>Descrição</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stadiumPlans as $plan)
                        <tr>
                            <td>{{ $plan->id }}</td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->stadium->name }}</td>
                            <td>{{ $plan->description }}</td>
                            <td>
                                <a href="{{ route('stadium_plans.edit', $plan->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('stadium_plans.destroy', $plan->id) }}" method="POST" style="display:inline;">
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
                <p class="text-muted">Total de plantas de estádio: {{ $stadiumPlans->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stadiumPlansTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush





