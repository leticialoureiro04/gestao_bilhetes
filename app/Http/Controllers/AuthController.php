<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\GESFaturacaoAPIController;

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

        // Gera o token de acesso do Laravel Sanctum para o usuário autenticado
        $appToken = $user->createToken('YourAppName')->plainTextToken;

        // Gera o token da API GESFaturação
        $gesfaturacaoController = app(GESFaturacaoAPIController::class); // Use o container para injetar dependências
        $apiResponse = $gesfaturacaoController->loginAPI();

        if ($apiResponse->getStatusCode() === 200 && $apiResponse->getData()->success) {
            $apiToken = $apiResponse->getData()->token;

            // Associa o token ao usuário logado na tabela api_tokens
            DB::table('api_tokens')->updateOrInsert(
                ['service' => 'gesfaturacao', 'user_id' => $user->id],
                ['token' => $apiToken, 'created_at' => now(), 'updated_at' => now()]
            );

            // Retorna a resposta JSON contendo ambos os tokens
            return response()->json([
                'user' => $user,
                'app_token' => $appToken, // Token para autenticação local
                'api_token' => $apiToken  // Token da API GESFaturação
            ]);
        } else {
            Log::error("Falha ao obter o token da API GESFaturação.");
            return response()->json([
                'user' => $user,
                'app_token' => $appToken,
                'error' => 'Falha ao conectar à API GESFaturação.'
            ], 500);
        }
    } else {
        return response()->json(['error' => 'Credenciais inválidas.'], 401);
    }
}



}


