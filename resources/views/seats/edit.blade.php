@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Editar Lugar</h3>
        </div>
        <form action="{{ route('seats.update', $seat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="stadium_plan_id">Planta do Estádio</label>
                    <select class="form-control" id="stadium_plan_id" name="stadium_plan_id" required>
                        <option value="">Selecione a Planta</option>
                        @foreach($stadiumPlans as $plan)
                            <option value="{{ $plan->id }}" {{ $seat->stadium_plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->stadium->name }} - {{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="seat_type_id">Tipo de Lugar</label>
                    <select class="form-control" id="seat_type_id" name="seat_type_id" required>
                        <option value="">Selecione o Tipo</option>
                        @foreach($seatTypes as $type)
                            <option value="{{ $type->id }}" {{ $seat->seat_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="disponível" {{ $seat->status == 'disponível' ? 'selected' : '' }}>Disponível</option>
                        <option value="reservado" {{ $seat->status == 'reservado' ? 'selected' : '' }}>Reservado</option>
                        <option value="vendido" {{ $seat->status == 'vendido' ? 'selected' : '' }}>Vendido</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('seats.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-warning">Atualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

