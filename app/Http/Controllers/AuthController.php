<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    /**
     * Função para login e geração de tokens personalizados.
     */
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Geração de um token simples (exemplo)
        $token = base64_encode($user->email . '|' . now());

        return response()->json(['token' => $token]);
    }

    return response()->json(['error' => 'Unauthorized'], 401);
}


    /**
     * Método para renovar o token.
     */
    public function renewToken(Request $request)
    {
        // Chamar o método de login para gerar um novo token
        return $this->login($request);
    }
}
