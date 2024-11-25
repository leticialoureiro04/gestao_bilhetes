<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        // Usa o idioma salvo na sessão ou o idioma padrão
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale); // Define o idioma da aplicação

        return $next($request);
    }
}





