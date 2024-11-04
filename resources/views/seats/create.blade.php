<div class="container">
    <h1>Adicionar Lugar</h1>

    <form action="{{ route('seats.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="stadium_plan_id" class="form-label">Planta do Estádio</label>
            <select class="form-control" id="stadium_plan_id" name="stadium_plan_id" required>
                <option value="">Selecione a Planta do Estádio</option>
                @foreach($stadiumPlans as $plan)
                    <option value="{{ $plan->id }}">
                        {{ $plan->stadium->name }} - {{ $plan->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="seat_type_id" class="form-label">Tipo de Lugar</label>
            <select class="form-control" id="seat_type_id" name="seat_type_id" required>
                <option value="">Selecione o Tipo</option>
                @foreach($seatTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="disponível">Disponível</option>
                <option value="reservado">Reservado</option>
                <option value="vendido">Vendido</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

