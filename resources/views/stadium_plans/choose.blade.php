@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Choose Plant for the Game</h3>
    <ul>
        @foreach($stadiumPlans as $plan)
            <li>
                <a href="{{ route('stadium.plan.seats', $plan->id) }}">
                    {{ $plan->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection

