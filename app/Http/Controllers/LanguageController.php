<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function change(Request $request)
    {
        $language = $request->input('language'); // Captura o idioma enviado pelo formulário
        $availableLanguages = ['pt', 'en']; // Idiomas disponíveis

        if (in_array($language, $availableLanguages)) {
            Session::put('locale', $language); // Salva o idioma na sessão
            App::setLocale($language); // Define o idioma na aplicação

            logger()->info('Idioma salvo na sessão:', ['locale' => Session::get('locale')]);
        }

        return redirect()->back(); // Redireciona de volta para a página atual
    }
}






