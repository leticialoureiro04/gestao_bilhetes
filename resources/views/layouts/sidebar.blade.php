<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('images/logo2.png') }}" alt="GesBilhetes Logo" class="brand-image img-circle elevation-3" style="opacity: .8; width: 50px; height: 50px;">
        <span class="brand-text font-weight-bold">GesBilhetes</span>
    </a>


    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('adminlte/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    {{ Auth::check() ? Auth::user()->name : __('sidebar.visitor') }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard Link -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('sidebar.dashboard') }}</p>
                    </a>
                </li>

                <!-- Condicional para verificar se o utilizador é um admin -->
                @if(auth()->check() && auth()->user()->hasRole('admin'))
                    <!-- Administração Dropdown Menu para Administradores -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>{{ __('sidebar.administration') }}</p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('/users') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('sidebar.manage_users') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('seat_types.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('sidebar.manage_seat_types') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('seats.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('sidebar.manage_seats') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('sidebar.manage_roles') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('teams.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('sidebar.manage_teams') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('games.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('sidebar.manage_games') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tickets.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('sidebar.manage_tickets') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Condicional para verificar se o utilizador é um admin -->
                @if(auth()->check() && auth()->user()->hasRole('cliente'))
                    <li class="nav-item">
                        <a href="{{ route('games.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('sidebar.buy_tickets') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('invoices.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('sidebar.view_invoices') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('sidebar.my_tickets') }}</p>
                        </a>
                    </li>
                @endif


                <!-- Condicional para verificar se o utilizador é um admin -->
                @if(auth()->check() && auth()->user()->hasRole('gestor de estadio'))
                    <li class="nav-item">
                        <a href="{{ route('seat_types.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('sidebar.manage_seat_types') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('seats.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('sidebar.manage_seats') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('stadiums.index') }}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>{{ __('sidebar.manage_stadiums') }}</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
