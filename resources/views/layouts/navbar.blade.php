<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">{{ __('navbar.home') }}</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- User Account Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="{{ route('profile') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> {{ __('navbar.profile') }}
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> {{ __('navbar.logout') }}
                    </button>
                </form>
            </div>
        </li>

        <!-- Language Switcher -->
        <li class="nav-item dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-expanded="false">
                {{ config('languages')[session('locale', config('app.locale'))] }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                @foreach (config('languages') as $lang => $language)
                    <li>
                        <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}">{{ $language }}</a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</nav>





