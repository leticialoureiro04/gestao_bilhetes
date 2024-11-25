@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Edit Seat</h3>
        </div>
        <form action="{{ route('seats.update', $seat->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <!--<div class="form-group">
                    <label for="stadium_plan_id">Planta do Estádio</label>
                    <select class="form-control" id="stadium_plan_id" name="stadium_plan_id" required>
                        <option value="">Selecione a Planta</option>
                        @foreach($stadiumPlans as $plan)
                            <option value="{{ $plan->id }}" {{ $seat->stadium_plan_id == $plan->id ? 'selected' : '' }}>
                                {{ $plan->stadium->name }} - {{ $plan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>-->

                <div class="form-group">
                    <label for="stand_id">Stand</label>
                    <select class="form-control" id="stand_id" name="stand_id" required>
                        <option value="">Select the Stand</option>
                        @foreach($stands as $stand)
                            <option value="{{ $stand->id }}" {{ $seat->stand_id == $stand->id ? 'selected' : '' }}>
                                {{ $stand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="row_number">Line Number</label>
                    <input type="number" class="form-control" id="row_number" name="row_number" value="{{ $seat->row_number }}" placeholder="Enter a line number" required>
                </div>

                <div class="form-group">
                    <label for="seat_number">Seat Number</label>
                    <input type="number" class="form-control" id="seat_number" name="seat_number" value="{{ $seat->seat_number }}" placeholder="Enter a seat number" required>
                </div>

                <div class="form-group">
                    <label for="seat_type_id">Type of Seat</label>
                    <select class="form-control" id="seat_type_id" name="seat_type_id" required>
                        <option value="">Select the type of seat</option>
                        @foreach($seatTypes as $type)
                            <option value="{{ $type->id }}" {{ $seat->seat_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
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
                    <i class="fas fa-arrow-left"></i> Come back
                </a>
                <button type="submit" class="btn btn-warning">Update Seat</button>
            </div>
        </form>
    </div>
</div>
@endsection


