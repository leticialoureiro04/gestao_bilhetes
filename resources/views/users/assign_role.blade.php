@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">{{ __('users.assign_role', ['name' => $user->name]) }}</h3>
        </div>
        <form action="{{ route('users.assign_role.store', $user->id) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="role">{{ __('users.select_role') }}</label>
                    <select class="form-control" name="role" id="role">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('users.come_back') }}
                </a>
                <button type="submit" class="btn btn-info">{{ __('users.assign') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection





