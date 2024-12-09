@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">{{ __('stadiums.stadium_view') }} {{ $stadium->name }}</h1>
    <div class="stadium-layout">
        <!-- Bancada Norte -->
        <div class="stand north">
            <h3 class="text-white">Bancada Norte</h3>
            @php $north = $stadium->stands->where('name', 'Bancada Norte')->first(); @endphp
            @if ($north)
                <p class="text-white">{{ __('stadiums.lines') }}:  {{ $north->num_rows }}</p>
                <p class="text-white">{{ __('stadiums.seats_per_row') }}: {{ $north->seats_per_row }}</p>
                <a href="{{ route('stands.view', $north->id) }}" class="btn btn-light btn-sm mt-2">{{ __('stadiums.view_stands') }}</a>
            @else
                <p class="text-white">{{ __('stadiums.no_information') }}</p>
            @endif
        </div>

        <!-- Bancada Nascente -->
        <div class="stand east">
            <h3 class="text-white"> Bancada Nascente </h3>
            @php $east = $stadium->stands->where('name', 'Bancada Nascente')->first(); @endphp
            @if ($east)
                <p class="text-white">{{ __('stadiums.lines') }}: {{ $east->num_rows }}</p>
                <p class="text-white">{{ __('stadiums.seats_per_row') }}: {{ $east->seats_per_row }}</p>
                <a href="{{ route('stands.view', $east->id) }}" class="btn btn-light btn-sm mt-2">{{ __('stadiums.view_stands') }}</a>
            @else
                <p class="text-white">{{ __('stadiums.no_information') }}</p>
            @endif
        </div>

        <!-- Bancada Sul -->
        <div class="stand south">
            <h3 class="text-white"> Bancada Sul</h3>
            @php $south = $stadium->stands->where('name', 'Bancada Sul')->first(); @endphp
            @if ($south)
                <p class="text-white">{{ __('stadiums.lines') }}: {{ $south->num_rows }}</p>
                <p class="text-white">{{ __('stadiums.seats_per_row') }}: {{ $south->seats_per_row }}</p>
                <a href="{{ route('stands.view', $south->id) }}" class="btn btn-light btn-sm mt-2">{{ __('stadiums.view_stands') }}</a>
            @else
                <p class="text-white">{{ __('stadiums.no_information') }}</p>
            @endif
        </div>

        <!-- Bancada Poente -->
        <div class="stand west">
            <h3 class="text-white"> Bancada Poente</h3>
            @php $west = $stadium->stands->where('name', 'Bancada Poente')->first(); @endphp
            @if ($west)
                <p class="text-white">{{ __('stadiums.lines') }}: {{ $west->num_rows }}</p>
                <p class="text-white">{{ __('stadiums.seats_per_row') }}: {{ $west->seats_per_row }}</p>
                <a href="{{ route('stands.view', $west->id) }}" class="btn btn-light btn-sm mt-2">{{ __('stadiums.view_stands') }}</a>
            @else
                <p class="text-white">{{ __('stadiums.no_information') }}</p>
            @endif
        </div>

        <!-- Campo -->
        <div class="field">
            <h3>Field</h3>
        </div>
    </div>
</div>

<style>
    .stadium-layout {
        display: grid;
        grid-template-areas:
            "north north north"
            "west field east"
            "south south south";
        grid-template-rows: 1fr 3fr 1fr;
        grid-template-columns: 1fr 3fr 1fr;
        gap: 10px;
        margin-top: 20px;
    }

    .stand {
        background-color: #343a40; /* Fundo cinza escuro */
        border: 1px solid #ccc;
        padding: 20px;
        text-align: center;
        font-weight: bold;
        border-radius: 8px;
    }

    .north {
        grid-area: north;
        background-color: #007bff; /* Azul */
    }

    .south {
        grid-area: south;
        background-color: #dc3545; /* Vermelho */
    }

    .east {
        grid-area: east;
        background-color: #f48608; /* Laranja */
    }

    .west {
        grid-area: west;
        background-color: #ffc107; /* Amarelo */
    }

    .field {
        grid-area: field;
        background-color: #007f00; /* Verde do campo */
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        font-size: 18px;
        border-radius: 8px;
    }

    .text-white {
        color: white;
    }
</style>
@endsection





