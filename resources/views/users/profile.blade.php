@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">{{ __('users.profile_title', ['name' => Auth::user()->name]) }}</h3>
        </div>
        <div class="card-body">
            <p><strong>{{ __('users.name') }}:</strong> {{ Auth::user()->name }}</p>
            <p><strong>{{ __('users.email') }}:</strong> {{ Auth::user()->email }}</p>
            <p><strong>{{ __('users.role') }}:</strong>
                {{ Auth::user()->roles->pluck('name')->implode(', ') ?: __('users.paperless') }}
            </p>
        </div>
        <div class="card-footer">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('users.back_dashboard') }}
            </a>
        </div>
    </div>
</div>
@endsection


