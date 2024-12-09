@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Editar Planta de Estádio</h3>
        </div>
        <form action="{{ route('stadium_plans.update', $stadiumPlan->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="stadium_id">Estádio</label>
                    <select class="form-control" id="stadium_id" name="stadium_id" required>
                        <option value="">Selecione um estádio</option>
                        @foreach($stadiums as $stadium)
                            <option value="{{ $stadium->id }}" {{ $stadium->id == $stadiumPlan->stadium_id ? 'selected' : '' }}>
                                {{ $stadium->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Nome da Planta</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $stadiumPlan->name }}" required>
                </div>
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ $stadiumPlan->description }}</textarea>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('stadium_plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-warning">Atualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection

