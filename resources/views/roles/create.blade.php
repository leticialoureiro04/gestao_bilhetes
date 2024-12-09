@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ __('roles.add_role') }}</h3>
        </div>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">{{ __('roles.role_name') }}</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('roles.role_name') }}" required>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('roles.come_back') }}
                </a>
                <button type="submit" class="btn btn-primary">{{ __('roles.save') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection


