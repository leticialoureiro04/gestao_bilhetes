<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http; // Facade para requisições HTTP
use Illuminate\Support\Facades\Log;  // Para logging
use Illuminate\Support\Facades\Config;

class GESFaturacaoAPIController extends Controller
{
    private $apiUrl;
    private $apiVersion;
    private $token;

    // Configuração inicial (carrega do arquivo services.php)
    public function __construct()
    {
        $this->apiUrl = config('services.gesfaturacao.url');
        $this->apiVersion = config('services.gesfaturacao.version');
        $this->token = config('services.gesfaturacao.token'); // Obtém o token diretamente do .env

        // Garantir que as configurações estão corretas
        if (empty($this->apiUrl) || empty($this->apiVersion) || empty($this->token)) {
            Log::error('Configuração da API GESFaturação está incompleta. Verifique o arquivo .env.');
            throw new \Exception('Configuração da API GESFaturação está incompleta.');
        }
    }

    /**
     * Obtém o token atual.
     * Este método retorna diretamente o token configurado no .env.
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token; // Retorna o token diretamente do .env
    }

    /**
 * Valida o token atual na API GESFaturação.
 *
 * @return array Resposta da API
 */
public function validateToken(): array
{
    $url = "{$this->apiUrl}/{$this->apiVersion}/validate-token"; // Endpoint de validação
    $token = $this->getToken(); // Obtém o token do .env

    try {
        // Fazendo a requisição POST para validar o token
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->post($url);

        // Verifica se a resposta foi bem-sucedida
        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        // Se a resposta não for bem-sucedida, retorna o erro
        return [
            'success' => false,
            'message' => 'Token inválido ou expirado.',
            'response' => $response->body(),
        ];
    } catch (\Exception $e) {
        // Captura erros e retorna
        Log::error('Erro ao validar token: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Erro ao conectar à API para validar o token.',
        ];
    }
}


    /**
     * Cria uma nova fatura na API.
     *
     * @param array $data Dados da fatura
     * @return array Resposta da API
     */
    public function createInvoice(array $data): array
    {
        $url = "{$this->apiUrl}/{$this->apiVersion}/invoices"; // Endpoint de criação de fatura

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->getToken()}", // Usa o token atual
                'Content-Type' => 'application/json',
            ])->post($url, $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Erro ao criar fatura. Resposta: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Erro ao criar fatura. Resposta: ' . $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('Erro ao criar fatura: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao conectar à API para criar fatura.',
            ];
        }
    }
}
