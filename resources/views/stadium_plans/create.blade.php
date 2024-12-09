@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Criar Nova Planta de Estádio</h3>
        </div>
        <form action="{{ route('stadium_plans.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="stadium_id">Estádio</label>
                    <select class="form-control" id="stadium_id" name="stadium_id" required>
                        <option value="">Selecione um estádio</option>
                        @foreach($stadiums as $stadium)
                            <option value="{{ $stadium->id }}">{{ $stadium->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Nome da Planta</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('stadium_plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

