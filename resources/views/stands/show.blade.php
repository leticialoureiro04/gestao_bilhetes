@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ __('stands.stadium_stands') }}: {{ $stadium->name }}</h2>

    @foreach($stadium->stands as $stand)
        <div class="card my-4">
            <div class="card-header">
                <h4>{{ __('stands.stand') }}: {{ $stand->name }}</h4>
            </div>
            <div class="card-body">
                <p><strong>{{ __('stands.number_of_lines') }}:</strong> {{ $stand->num_rows }}</p>
                <p><strong>{{ __('stands.seats_per_row') }}:</strong> {{ $stand->seats_per_row }}</p>
                <p><strong>{{ __('stands.total_seats') }}:</strong> {{ $stand->num_rows * $stand->seats_per_row }}</p>
            </div>
        </div>
    @endforeach

    <a href="{{ route('stadiums.index') }}" class="btn btn-secondary">{{ __('stands.back_to_stadium_list') }}</a>
</div>
@endsection


