<div class="container">
    <h1>Lista de Plantas de Estádio</h1>

    <a href="{{ route('stadium_plans.create') }}" class="btn btn-primary mb-3">Adicionar Planta de Estádio</a>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome da Planta</th>
                <th scope="col">Estádio</th>
                <th scope="col">Descrição</th>
                <th scope="col">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stadiumPlans as $plan)
                <tr>
                    <td>{{ $plan->id }}</td>
                    <td>{{ $plan->name }}</td>
                    <td>{{ $plan->stadium->name }}</td> <!-- Mostra o nome do estádio -->
                    <td>{{ $plan->description }}</td>
                    <td>
                        <a href="{{ route('stadium_plans.edit', $plan->id) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('stadium_plans.destroy', $plan->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Apagar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

