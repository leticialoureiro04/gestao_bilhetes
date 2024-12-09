@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="container-fluid">
    <!-- Seleção de Idioma -->
    <div class="d-flex justify-content-end mb-3">
    </div>

    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{__('messages.dashboard') }}
    </h2>

    <!-- Dashboard para Administrador -->
    @if(auth()->user()->hasRole('admin'))
        <div class="row mt-4">
            <!-- Card Estatísticas -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>150</h3>
                        <p>{{ __('messages.users') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ url('/users') }}" class="small-box-footer">{{ __('messages.manage_users') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>53</h3>
                        <p>{{ __('messages.stadiums') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="{{ route('stadiums.index') }}" class="small-box-footer">{{ __('messages.manage_stadiums') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-pink">
                    <div class="inner">
                        <h3>44</h3>
                        <p>{{ __('messages.games') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <a href="{{ route('games.index') }}" class="small-box-footer">{{ __('messages.manage_games') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>65</h3>
                        <p>{{ __('messages.tickets') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="small-box-footer">{{ __('messages.manage_tickets') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Novo Card para Exportar Relatórios -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ __('messages.reports') }}</h3>
                        <p>{{ __('messages.export') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-export"></i>
                    </div>
                    <a href="{{ route('relatorios.index') }}" class="small-box-footer">{{ __('messages.generate_reports') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3>{{ __('messages.invoices') }}</h3>
                        <p>{{ __('messages.manage_invoices') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <a href="{{ route('invoices.index') }}" class="small-box-footer">
                        {{ __('messages.go_to_invoices') }} <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-cyan">
                    <div class="inner">
                        <h3>{{ __('messages.seats') }}</h3>
                        <p>{{ __('messages.manage_seats') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chair"></i>
                    </div>
                    <a href="{{ route('seats.index') }}" class="small-box-footer">{{ __('messages.manage_seats') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{ __('messages.teams') }}</h3>
                        <p>{{ __('messages.manage_teams') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <a href="{{ route('teams.index') }}" class="small-box-footer">{{ __('messages.manage_teams') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

        </div>

        <!-- Gráficos -->
        <div class="row mt-4">
            <!-- Gráfico de Vendas por Semana -->
            <div class="col-lg-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('messages.sales_per_week') }}</h3>
                    </div>
                    <div class="card-body text-center">
                        <canvas id="salesChart" style="height: 200px; max-width: 90%;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Bilhetes por Tipo -->
            <div class="col-lg-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('messages.tickets_sold_by_type') }}</h3>
                    </div>
                    <div class="card-body text-center">
                        <canvas id="ticketsChart" style="height: 200px; max-width: 90%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Condicional para Cliente -->
    @if(auth()->user()->hasRole('cliente'))
        <div class="row mt-4">
            <!-- Card para Ver Bilhetes -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ __('messages.tickets') }}</h3>
                        <p>{{ __('messages.my_tickets') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="small-box-footer">{{ __('messages.view_tickets') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Card para Comprar Bilhetes -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-pink">
                    <div class="inner">
                        <h3>{{ __('messages.games') }}</h3>
                        <p>{{ __('messages.buy_tickets') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <a href="{{ route('games.index') }}" class="small-box-footer">{{ __('messages.buy_now') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <!-- Card para Faturas -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h3>{{ __('messages.invoices') }}</h3>
                        <p>{{ __('messages.view_invoices') }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <a href="{{ route('invoices.index') }}" class="small-box-footer">{{ __('messages.view_invoices') }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    @endif


    <!-- Condicional para Gestor de Estádio -->
@if(auth()->user()->hasRole('gestor de estadio'))
<div class="row mt-4">
    <!-- Card para Gestão de Estádios -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ __('messages.stadiums') }}</h3>
                <p>{{ __('messages.manage_stadiums') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-building"></i>
            </div>
            <a href="{{ route('stadiums.index') }}" class="small-box-footer">{{ __('messages.manage_stadiums') }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- Card para Gestão de Lugares -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-cyan">
            <div class="inner">
                <h3>{{ __('messages.seats') }}</h3>
                <p>{{ __('messages.manage_seats') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-chair"></i>
            </div>
            <a href="{{ route('seats.index') }}" class="small-box-footer">{{ __('messages.manage_seats') }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-purple">
            <div class="inner">
                <h3>{{ __('messages.seats_types') }}</h3>
                <p>{{ __('messages.manage_seat_types') }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-chair"></i>
            </div>
            <a href="{{ route('seat_types.index') }}" class="small-box-footer">{{ __('messages.manage_seat_types') }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>
@endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dados para os gráficos vindos do backend
    const salesData = @json($sales->pluck('total'));
    const salesLabels = @json($sales->pluck('week')).map((week) => `Week`);

    const ticketsData = @json($ticketsByType->pluck('total'));
    const ticketsLabels = @json($ticketsByType->pluck('type'));

    // Gráfico de Vendas por Semana
    if (document.getElementById('salesChart')) {
        const salesChartContext = document.getElementById('salesChart').getContext('2d');
        new Chart(salesChartContext, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: "{{ __('messages.sales') }}",
                    data: salesData,
                    borderColor: 'rgba(60, 141, 188, 1)',
                    backgroundColor: 'rgba(60, 141, 188, 0.5)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Gráfico de Bilhetes Vendidos por Tipo
    if (document.getElementById('ticketsChart')) {
        const ticketsChartContext = document.getElementById('ticketsChart').getContext('2d');
        new Chart(ticketsChartContext, {
            type: 'pie',
            data: {
                labels: ticketsLabels,
                datasets: [{
                    data: ticketsData,
                    backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
</script>
@endpush
