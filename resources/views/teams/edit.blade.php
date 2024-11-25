@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-warning">
        <div class="card-header">
            <h3 class="card-title">Edit Team</h3>
        </div>
        <form action="{{ route('teams.update', $team->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $team->name }}" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ $team->city }}">
                </div>
                <div class="form-group">
                    <label for="founded">Founding Date</label>
                    <input type="date" class="form-control" id="founded" name="founded" value="{{ $team->founded }}">
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('teams.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Come back
                </a>
                <button type="submit" class="btn btn-warning">Update Team</button>
            </div>
        </form>
    </div>
</div>
@endsection

