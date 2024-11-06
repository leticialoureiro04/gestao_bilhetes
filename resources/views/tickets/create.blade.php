@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Emitir Bilhete</h3>
        </div>
        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="game_id">Jogo</label>
                    <select name="game_id" id="game_id" class="form-control" required>
                        <option value="">Selecione um jogo</option>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}" data-stadium="{{ $game->stadium->name }}" data-stadium-id="{{ $game->stadium_id }}">
                                {{ $game->date_time }} - {{ $game->stadium->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="stadium">Estádio</label>
                    <input type="text" id="stadium" name="stadium" class="form-control" readonly>
                    <input type="hidden" name="stadium_id" id="stadium_id">
                </div>
                <div class="form-group">
                    <label for="stadium_plan_id">Planta do Estádio</label>
                    <select name="stadium_plan_id" id="stadium_plan_id" class="form-control" required>
                        <option value="">Selecione a planta do estádio</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="seat_type_id">Tipo de Lugar</label>
                    <select name="seat_type_id" id="seat_type_id" class="form-control" required>
                        <option value="">Selecione o tipo de lugar</option>
                        @foreach($seatTypes as $seatType)
                            <option value="{{ $seatType->id }}" data-price="{{ $seatType->price }}">{{ $seatType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="seat_id">Lugar</label>
                    <select name="seat_id" id="seat_id" class="form-control" required>
                        <option value="">Selecione um lugar</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Preço</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" readonly>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-primary">Emitir Bilhete</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Atualiza o campo de estádio quando o jogo é selecionado
        $('#game_id').change(function() {
            var stadiumName = $('#game_id option:selected').data('stadium');
            var stadiumId = $('#game_id option:selected').data('stadium-id');
            $('#stadium').val(stadiumName);
            $('#stadium_id').val(stadiumId);

            // Carrega as plantas do estádio selecionado
            if (stadiumId) {
                $.ajax({
                    url: '{{ route("getStadiumPlansByStadium") }}',
                    data: { stadium_id: stadiumId },
                    success: function(data) {
                        $('#stadium_plan_id').empty().append('<option value="">Selecione a planta do estádio</option>');
                        data.forEach(function(plan) {
                            $('#stadium_plan_id').append('<option value="' + plan.id + '">' + plan.name + '</option>');
                        });
                    }
                });
            } else {
                $('#stadium_plan_id').empty().append('<option value="">Selecione a planta do estádio</option>');
                $('#seat_id').empty().append('<option value="">Selecione um lugar</option>');
            }
        });

        // Atualiza o campo de preço quando o tipo de lugar é selecionado
        $('#seat_type_id').change(function() {
            var price = $('#seat_type_id option:selected').data('price');
            $('#price').val(price);
        });

        // Redireciona para a página interativa de escolha de lugares ao selecionar uma planta
        $('#stadium_plan_id').change(function() {
            var planId = $(this).val();
            if (planId) {
                window.location.href = '/stadium-plan/' + planId + '/seats';
            } else {
                $('#seat_id').empty().append('<option value="">Selecione um lugar</option>');
            }
        });
    });
</script>
@endpush







