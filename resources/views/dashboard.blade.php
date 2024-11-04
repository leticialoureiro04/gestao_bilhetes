<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />

                {{-- Verificar se o utilizador tem o papel de 'admin' --}}
                @if(auth()->user()->hasRole('admin'))
                    <div class="mt-4">
                        <a href="{{ url('/users') }}" class="btn btn-primary">Gerir Utilizadores</a>
                        <a href="{{ route('stadiums.index') }}" class="btn btn-primary">Gerir Estádios</a>
                        <a href="{{ route('stadium_plans.index') }}" class="btn btn-primary">Gerir Plantas de Estádio</a>
                        <a href="{{ route('games.index') }}" class="btn btn-primary">Gerir Jogos</a>
                        <a href="{{ route('seat_types.index') }}" class="btn btn-primary">Gerir Tipos de Lugares</a>
                        <a href="{{ route('seats.index') }}" class="btn btn-primary">Gerir Lugares</a>
                        <a href="{{ route('roles.index') }}" class="btn btn-primary">Lista de roles</a>
                        <a href="{{ route('teams.index') }}" class="btn btn-primary">Gerir Equipas</a>
                        <a href="{{ route('tickets.index') }}" class="btn btn-primary">Gestão de Bilhetes</a>
                    </div>
                @endif

                {{-- Verificar se o utilizador tem a permissão de 'buy tickets' --}}
                @if(auth()->user()->can('buy tickets'))
                    <div class="mt-4">
                        <a href="{{ route('tickets.index') }}" class="btn btn-primary">Ver Bilhetes</a>
                        <a href="{{ route('tickets.create') }}" class="btn btn-primary">Comprar Bilhete</a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
