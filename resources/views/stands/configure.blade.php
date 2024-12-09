@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('stands.configure_stands_for') }} {{ $stadium->name }}</h3>
        </div>
        <form action="{{ route('stadiums.view', $stadium->id) }}" method="POST">
            @csrf
            <input type="hidden" name="stadium_id" value="{{ $stadium->id }}">

            <div class="card-body">
                <h5>{{ __('stands.set_stadium_stands') }}</h5>

                @for ($i = 1; $i <= $stadium->num_stands; $i++)
                    <div class="form-group">
                        <label for="stand_name_{{ $i }}">{{ __('stands.stand_name') }} {{ $i }}</label>
                        <input type="text" class="form-control" id="stand_name_{{ $i }}" name="stands[{{ $i }}][name]" placeholder="{{ __('stands.stand_name') }}" required>

                        <label for="num_rows_{{ $i }}">{{ __('stands.number_of_rows') }}</label>
                        <input type="number" class="form-control" id="num_rows_{{ $i }}" name="stands[{{ $i }}][num_rows]" placeholder="{{ __('stands.number_of_rows') }}" required>

                        <label for="seats_per_row_{{ $i }}">{{ __('stands.seats_per_row') }}</label>
                        <input type="number" class="form-control" id="seats_per_row_{{ $i }}" name="stands[{{ $i }}][seats_per_row]" placeholder="{{ __('stands.seats_per_row') }}" required>

                        <hr>

                        <h6>{{ __('stands.configure_stands_for') }} {{ $i }}</h6>
                        <div class="form-group">
                            <label for="vip_seats_{{ $i }}">{{ __('stands.vip_seats') }}</label>
                            <input type="number" class="form-control" id="vip_seats_{{ $i }}" name="stands[{{ $i }}][seat_types][1]" placeholder="{{ __('stands.vip_seats') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="normal_seats_{{ $i }}">{{ __('stands.normal_seats') }}</label>
                            <input type="number" class="form-control" id="normal_seats_{{ $i }}" name="stands[{{ $i }}][seat_types][2]" placeholder="{{ __('stands.normal_seats') }}" required>
                        </div>
                    </div>
                    <hr>
                @endfor
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success ml-2">{{ __('stands.view_stadium') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection




