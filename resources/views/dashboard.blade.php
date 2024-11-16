@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <div class="container-fluid">
        <!-- Seleção de Idioma -->
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('language.change') }}" method="POST">
                @csrf
                <select name="language" onchange="this.form.submit()" class="form-control" style="width: auto;">
                    <option value="pt" {{ app()->getLocale() == 'pt' ? 'selected' : '' }}>Português</option>
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                    <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>Español</option>
                </select>
            </form>
        </div>

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('messages.Dashboard') }}
        </h2>

        <!-- Dashboard para Administrador -->
        @if(auth()->user()->hasRole('admin'))
            <div class="row mt-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>
                            <p>{{ __('messages.Utilizadores') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ url('/users') }}" class="small-box-footer">{{ __('messages.Gerir Utilizadores') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53</h3>
                            <p>{{ __('messages.Estádios') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <a href="{{ route('stadiums.index') }}" class="small-box-footer">{{ __('messages.Gerir Estádios') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>
                            <p>{{ __('messages.Jogos') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-futbol"></i>
                        </div>
                        <a href="{{ route('games.index') }}" class="small-box-footer">{{ __('messages.Gerir Jogos') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>
                            <p>{{ __('messages.Bilhetes') }}</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <a href="{{ route('tickets.index') }}" class="small-box-footer">{{ __('messages.Gerir Bilhetes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        @else
            <!-- Dashboard simplificado para Cliente -->
            <div class="row mt-4">
                <div class="col-lg-6 col-12">
                    <div class="small-box bg-primary" style="position: relative; overflow: hidden;">
                        <div class="inner text-center py-3">
                            <h4 class="font-weight-bold">{{ __('messages.Ver Bilhetes') }}</h4>
                            <p>{{ __('messages.Aceda aos bilhetes disponíveis') }}</p>
                        </div>
                        <div class="icon" style="top: 50%; right: 15px; font-size: 2.5rem; transform: translateY(-50%); position: absolute; opacity: 0.15;">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <a href="{{ route('tickets.index') }}" class="small-box-footer">{{ __('messages.Ver Bilhetes') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-6 col-12">
                    <div class="small-box bg-success" style="position: relative; overflow: hidden;">
                        <div class="inner text-center py-3">
                            <h4 class="font-weight-bold">{{ __('messages.Comprar Bilhete') }}</h4>
                            <p>{{ __('messages.Adquira novos bilhetes') }}</p>
                        </div>
                        <div class="icon" style="top: 50%; right: 15px; font-size: 2.5rem; transform: translateY(-50%); position: absolute; opacity: 0.15;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('tickets.create') }}" class="small-box-footer">{{ __('messages.Comprar Bilhete') }} <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
















