@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Adicionar Lugar</h3>
        </div>
        <form action="{{ route('seats.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="stadium_plan_id">Planta do Estádio</label>
                    <select class="form-control" id="stadium_plan_id" name="stadium_plan_id" required>
                        <option value="">Selecione a Planta do Estádio</option>
                        @foreach($stadiumPlans as $plan)
                            <option value="{{ $plan->id }}">
                                {{ $plan->stadium->name }} - {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="stand_id">Bancada</label>
                    <select class="form-control" id="stand_id" name="stand_id" required>
                        <option value="">Selecione a Bancada</option>
                        @foreach($stands as $stand)
                            <option value="{{ $stand->id }}">{{ $stand->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="row_number">Número da Linha</label>
                    <input type="number" class="form-control" id="row_number" name="row_number" placeholder="Introduza o número da linha" required>
                </div>

                <div class="form-group">
                    <label for="seat_number">Número do Assento</label>
                    <input type="number" class="form-control" id="seat_number" name="seat_number" placeholder="Introduza o número do assento" required>
                </div>

                <div class="form-group">
                    <label for="seat_type_id">Tipo de Lugar</label>
                    <select class="form-control" id="seat_type_id" name="seat_type_id" required>
                        <option value="">Selecione o Tipo</option>
                        @foreach($seatTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="disponível">Disponível</option>
                        <option value="reservado">Reservado</option>
                        <option value="vendido">Vendido</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('seats.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection



