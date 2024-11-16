<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function change(Request $request)
    {
        $language = $request->input('language');
        $availableLanguages = ['pt', 'en', 'es'];

        if (in_array($language, $availableLanguages)) {
            Session::put('locale', $language);
            App::setLocale($language);
        }

        return redirect()->back();
    }
}




