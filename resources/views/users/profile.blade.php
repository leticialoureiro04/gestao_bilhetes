@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Profile {{ Auth::user()->name }}</h3>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Paper:</strong> {{ Auth::user()->roles->pluck('name')->implode(', ') }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection

