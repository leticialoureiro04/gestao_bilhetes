<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        // Use o valor da sessão 'locale' ou o padrão configurado
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        return $next($request);
    }
}



