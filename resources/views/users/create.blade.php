@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('users.add_user') }}</h3>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">{{ __('users.name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('users.name') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">{{ __('users.email') }}</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('users.email') }}" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('users.come_back') }}
                </a>
                <button type="submit" class="btn btn-primary">{{ __('users.add_user') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection

