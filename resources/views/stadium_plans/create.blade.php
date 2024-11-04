<div class="container">
    <h1>Criar nova planta de estádio</h1>

    <form action="{{ route('stadium_plans.store') }}" method="POST">
        @csrf
        <!-- Selecionar Estádio -->
        <div class="mb-3">
            <label for="stadium_id" class="form-label">Estádio</label>
            <select class="form-control" id="stadium_id" name="stadium_id" required>
                <option value="">Selecione um estádio</option>
                @foreach($stadiums as $stadium)
                    <option value="{{ $stadium->id }}">{{ $stadium->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Campos para criar a planta -->
        <div class="mb-3">
            <label for="name" class="form-label">Nome da Planta</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>
