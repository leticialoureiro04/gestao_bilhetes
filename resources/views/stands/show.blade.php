@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Stadium Stands: {{ $stadium->name }}</h2>

    @foreach($stadium->stands as $stand)
        <div class="card my-4">
            <div class="card-header">
                <h4>Stand: {{ $stand->name }}</h4>
            </div>
            <div class="card-body">
                <p><strong>Number of Lines:</strong> {{ $stand->num_rows }}</p>
                <p><strong>Seats per Row:</strong> {{ $stand->seats_per_row }}</p>
                <p><strong>Total Seats:</strong> {{ $stand->num_rows * $stand->seats_per_row }}</p>
            </div>
        </div>
    @endforeach

    <a href="{{ route('stadiums.index') }}" class="btn btn-secondary">Back to Stadium List</a>
</div>
@endsection

