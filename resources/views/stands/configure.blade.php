@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Configure Stands for {{ $stadium->name }}</h3>
        </div>
        <form action="{{ route('stadiums.view', $stadium->id) }}" method="POST">
            @csrf
            <input type="hidden" name="stadium_id" value="{{ $stadium->id }}">

            <div class="card-body">
                <h5>Set the stadium stands:</h5>

                @for ($i = 1; $i <= $stadium->num_stands; $i++)
                    <div class="form-group">
                        <label for="stand_name_{{ $i }}">Stand Name {{ $i }}</label>
                        <input type="text" class="form-control" id="stand_name_{{ $i }}" name="stands[{{ $i }}][name]" placeholder="Stand Name" required>

                        <label for="num_rows_{{ $i }}">Number of Rows</label>
                        <input type="number" class="form-control" id="num_rows_{{ $i }}" name="stands[{{ $i }}][num_rows]" placeholder="Number of Rows" required>

                        <label for="seats_per_row_{{ $i }}">Seats per Row</label>
                        <input type="number" class="form-control" id="seats_per_row_{{ $i }}" name="stands[{{ $i }}][seats_per_row]" placeholder="Seats per Row" required>

                        <hr>

                        <h6>Configuring Seat Types for the Stand {{ $i }}</h6>
                        <div class="form-group">
                            <label for="vip_seats_{{ $i }}">VIP Seats</label>
                            <input type="number" class="form-control" id="vip_seats_{{ $i }}" name="stands[{{ $i }}][seat_types][1]" placeholder="Number of VIP Seats" required>
                        </div>
                        <div class="form-group">
                            <label for="normal_seats_{{ $i }}">Normal Seats</label>
                            <input type="number" class="form-control" id="normal_seats_{{ $i }}" name="stands[{{ $i }}][seat_types][2]" placeholder="Number of Normal Seats" required>
                        </div>
                    </div>
                    <hr>
                @endfor
            </div>

            <div class="card-footer">
                <!-- Salvar e visualizar o estádio -->
                <button type="submit" class="btn btn-success ml-2">View Stadium</button>
            </div>
        </form>
    </div>
</div>
@endsection



