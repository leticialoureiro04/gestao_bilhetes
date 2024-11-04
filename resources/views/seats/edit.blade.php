<div class="container">
    <h1>Editar Lugar</h1>

    <form action="{{ route('seats.update', $seat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="stadium_plan_id" class="form-label">Planta do Estádio</label>
            <select class="form-control" id="stadium_plan_id" name="stadium_plan_id" required>
                <option value="">Selecione a Planta</option>
                @foreach($stadiumPlans as $plan)
                    <option value="{{ $plan->id }}" {{ $seat->stadium_plan_id == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="seat_type_id" class="form-label">Tipo de Lugar</label>
            <select class="form-control" id="seat_type_id" name="seat_type_id" required>
                <option value="">Selecione o Tipo</option>
                @foreach($seatTypes as $type)
                    <option value="{{ $type->id }}" {{ $seat->seat_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="disponível" {{ $seat->status == 'disponível' ? 'selected' : '' }}>Disponível</option>
                <option value="reservado" {{ $seat->status == 'reservado' ? 'selected' : '' }}>Reservado</option>
                <option value="vendido" {{ $seat->status == 'vendido' ? 'selected' : '' }}>Vendido</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
