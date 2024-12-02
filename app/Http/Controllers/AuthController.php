<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    /**
     * Função para login e geração de tokens.
     */
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

    $token = $user->createToken('YourAppName');
    $plainTextToken = $token->plainTextToken;
    return response()->json(['token' => $plainTextToken]);  // Retorna o token de acesso gerado
    } else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

}

/**
     * Método para renovar o token.
     */
    public function renewToken(Request $request)
    {
        // Chame o método de login para gerar um novo token
        return $this->login($request);
    }

}


