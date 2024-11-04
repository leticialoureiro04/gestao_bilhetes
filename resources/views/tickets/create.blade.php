<div class="container">
    <h1>Emitir Bilhete</h1>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="game_id" class="form-label">Jogo</label>
            <select name="game_id" id="game_id" class="form-control" required>
                <option value="">Selecione um jogo</option>
                @foreach($games as $game)
                    <option value="{{ $game->id }}" data-stadium="{{ $game->stadium_id }}">{{ $game->date_time }} - {{ $game->stadium->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="stadium" class="form-label">Estádio</label>
            <input type="text" id="stadium" name="stadium" class="form-control" readonly>
            <input type="hidden" name="stadium_id" id="stadium_id">
        </div>

        <div class="mb-3">
            <label for="stadium_plan_id" class="form-label">Planta do Estádio</label>
            <select name="stadium_plan_id" id="stadium_plan_id" class="form-control" required>
                <option value="">Selecione a planta do estádio</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="seat_type_id" class="form-label">Tipo de Lugar</label>
            <select name="seat_type_id" id="seat_type_id" class="form-control" required>
                <option value="">Selecione o tipo de lugar</option>
                @foreach($seatTypes as $seatType)
                    <option value="{{ $seatType->id }}" data-price="{{ $seatType->price }}">{{ $seatType->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="seat_id" class="form-label">Lugar</label>
            <select name="seat_id" id="seat_id" class="form-control" required>
                <option value="">Selecione um lugar</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Preço</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Emitir Bilhete</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Seleciona o estádio automaticamente ao selecionar um jogo
    $('#game_id').change(function() {
        var stadiumId = $(this).find(':selected').data('stadium');
        var stadiumName = $(this).find(':selected').text().split(" - ")[1];

        $('#stadium_id').val(stadiumId);
        $('#stadium').val(stadiumName);

        $('#stadium_plan_id').empty().append('<option value="">Selecione a planta do estádio</option>');
        $('#seat_type_id').val('');
        $('#seat_id').empty().append('<option value="">Selecione um lugar</option>');

        if (stadiumId) {
            $.ajax({
                url: "{{ route('getStadiumPlansByStadium') }}",
                type: "GET",
                data: { stadium_id: stadiumId },
                success: function(plans) {
                    $('#stadium_plan_id').empty().append('<option value="">Selecione a planta do estádio</option>');
                    $.each(plans, function(key, plan) {
                        $('#stadium_plan_id').append('<option value="'+ plan.id +'">'+ plan.name +'</option>');
                    });
                }
            });
        }
    });

    // Atualiza o preço automaticamente ao selecionar o tipo de assento
    $('#seat_type_id').change(function() {
        var seatTypeId = $(this).val();
        var stadiumPlanId = $('#stadium_plan_id').val();

        if (seatTypeId && stadiumPlanId) {
            $.ajax({
                url: "{{ route('getSeatsByTypeAndPlan') }}",
                type: "GET",
                data: { stadium_plan_id: stadiumPlanId, seat_type_id: seatTypeId },
                success: function(seats) {
                    $('#seat_id').empty().append('<option value="">Selecione um lugar</option>');
                    $.each(seats, function(key, seat) {
                        $('#seat_id').append('<option value="'+ seat.id +'">Lugar ' + seat.id + '</option>');
                    });
                }
            });

            var price = $(this).find(':selected').data('price');
            $('#price').val(price ? price : '');
        } else {
            $('#seat_id').empty().append('<option value="">Selecione um lugar</option>');
            $('#price').val('');
        }
    });
</script>



