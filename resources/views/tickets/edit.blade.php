@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Edit Ticket</h3>
        </div>
        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="game_id">Game</label>
                    <select name="game_id" id="game_id" class="form-control" required>
                        @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ $game->id == $ticket->game_id ? 'selected' : '' }}>
                                {{ $game->stadium->name }} - {{ $game->date_time }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="seat_id">Seat</label>
                    <select name="seat_id" id="seat_id" class="form-control" required>
                        @foreach($seats as $seat)
                            <option value="{{ $seat->id }}" {{ $seat->id == $ticket->seat_id ? 'selected' : '' }}>
                                Saet {{ $seat->id }} - Plant: {{ $seat->stadiumPlan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="user_id">User</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $ticket->user_id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ $ticket->price }}" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="disponível" {{ $ticket->status == 'disponível' ? 'selected' : '' }}>Available</option>
                        <option value="vendido" {{ $ticket->status == 'vendido' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Come back
                </a>
                <button type="submit" class="btn btn-warning">Update Ticket</button>
            </div>
        </form>
    </div>
</div>
@endsection

