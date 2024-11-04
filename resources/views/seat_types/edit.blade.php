
    <div class="container">
        <h1>Editar Tipo de Lugar</h1>

        <form action="{{ route('seat_types.update', $seatType->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nome do Tipo de Lugar</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $seatType->name }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $seatType->description }}</textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Preço</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $seatType->price }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>
