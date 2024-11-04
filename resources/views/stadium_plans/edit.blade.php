<div class="container">
    <h1>Editar Planta de Estádio</h1>

    <form action="{{ route('stadium_plans.update', $stadiumPlan->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Selecionar Estádio -->
        <div class="mb-3">
            <label for="stadium_id" class="form-label">Estádio</label>
            <select class="form-control" id="stadium_id" name="stadium_id" required>
                <option value="">Selecione um estádio</option>
                @foreach($stadiums as $stadium)
                    <option value="{{ $stadium->id }}" {{ $stadium->id == $stadiumPlan->stadium_id ? 'selected' : '' }}>
                        {{ $stadium->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Campos para editar a planta -->
        <div class="mb-3">
            <label for="name" class="form-label">Nome da Planta</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $stadiumPlan->name }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $stadiumPlan->description }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
