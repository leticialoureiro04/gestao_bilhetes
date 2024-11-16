@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Adicionar Estádio</h3>
        </div>
        <!-- /.card-header -->
        <form action="{{ route('stadiums.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Introduza o nome" required>
                </div>
                <div class="form-group">
                    <label for="location">Localização</label>
                    <input type="text" class="form-control" name="location" id="location" placeholder="Introduza a localização" required>
                </div>
                <div class="form-group">
                    <label for="capacity">Capacidade</label>
                    <input type="number" class="form-control" name="capacity" id="capacity" placeholder="Introduza a capacidade" required>
                </div>
                <div class="form-group">
                    <label for="num_stands">Número de Bancadas</label>
                    <input type="number" class="form-control" name="num_stands" id="num_stands" placeholder="Escolha o número de bancadas (1-4)" min="1" max="4" required>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('stadiums.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">Criar Estádio</button>
            </div>
        </form>
    </div>
</div>
@endsection

