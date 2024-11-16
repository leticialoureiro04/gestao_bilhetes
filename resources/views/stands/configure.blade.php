@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Configurar Bancadas para {{ $stadium->name }}</h3>
        </div>
        <form action="{{ route('stands.store') }}" method="POST">
            @csrf
            <input type="hidden" name="stadium_id" value="{{ $stadium->id }}">

            <div class="card-body">
                <h5>Defina as bancadas do estádio:</h5>

                @for ($i = 1; $i <= $stadium->num_stands; $i++)
                    <div class="form-group">
                        <label for="stand_name_{{ $i }}">Nome da Bancada {{ $i }}</label>
                        <input type="text" class="form-control" id="stand_name_{{ $i }}" name="stands[{{ $i }}][name]" placeholder="Nome da Bancada" required>

                        <label for="num_rows_{{ $i }}">Número de Linhas</label>
                        <input type="number" class="form-control" id="num_rows_{{ $i }}" name="stands[{{ $i }}][num_rows]" placeholder="Número de Linhas" required>

                        <label for="seats_per_row_{{ $i }}">Assentos por Linha</label>
                        <input type="number" class="form-control" id="seats_per_row_{{ $i }}" name="stands[{{ $i }}][seats_per_row]" placeholder="Assentos por Linha" required>

                        <hr>

                        <h6>Configuração de Tipos de Lugar para a Bancada {{ $i }}</h6>
                        <div class="form-group">
                            <label for="vip_seats_{{ $i }}">Lugares VIP</label>
                            <input type="number" class="form-control" id="vip_seats_{{ $i }}" name="stands[{{ $i }}][seat_types][1]" placeholder="Quantidade de Lugares VIP" required>
                        </div>
                        <div class="form-group">
                            <label for="normal_seats_{{ $i }}">Lugares Normais</label>
                            <input type="number" class="form-control" id="normal_seats_{{ $i }}" name="stands[{{ $i }}][seat_types][2]" placeholder="Quantidade de Lugares Normais" required>
                        </div>
                    </div>
                    <hr>
                @endfor
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Guardar Bancadas</button>
            </div>
        </form>
    </div>
</div>
@endsection


