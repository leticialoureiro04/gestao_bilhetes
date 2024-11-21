<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GesFaturacaoService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        // Carrega as variáveis do .env
        $this->baseUrl = 'https://devipvc.gesfaturacao.pt/api'; // Teste com a URL diretamente

        $this->token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjo5LCJ1c2VybmFtZSI6ImlwdmMiLCJjcmVhdGVkIjoiMjAyNC0xMS0yMSAwOTo0OTo0NyJ9.jV6xan1y1xpdqVfWxrEb1ToXVbzQpuOU0_ADUyqbPSQ';

    }

    /**
     * Cria uma nova fatura na API GESFaturação.
     *
     * @param array $data Dados da fatura a serem enviados para a API.
     * @return array Resultado da requisição (sucesso ou erro).
     */
    public function createInvoice(array $data)
    {
        Log::info('Token utilizado para API:', ['token' => $this->token]);

        Log::info('Payload enviado para a API:', ['data' => $data]);
        try {
            $response = Http::withToken($this->token)
                ->post("{$this->baseUrl}/invoices", $data);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Erro na criação de fatura:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message', 'Erro desconhecido'),
            ];
        } catch (\Exception $e) {
            Log::error('Exceção ao criar fatura:', ['message' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Erro ao comunicar com a API.',
            ];
        }
    }


    /**
     * Lista todas as faturas disponíveis na API GESFaturação.
     *
     * @return array Resultado da requisição (lista de faturas ou erro).
     */
    public function listInvoices()
    {
        // Faz a requisição GET para listar faturas
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/invoices");

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'message' => $response->json('message', 'Erro desconhecido'),
        ];
    }

    /**
     * Busca os detalhes de uma fatura específica.
     *
     * @param int $invoiceId ID da fatura.
     * @return array Resultado da requisição (dados da fatura ou erro).
     */
    public function getInvoiceDetails(int $invoiceId)
    {
        // Faz a requisição GET para obter os detalhes de uma fatura
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/invoices/{$invoiceId}");

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'message' => $response->json('message', 'Erro desconhecido'),
        ];
    }

    /**
     * Atualiza o status de uma fatura.
     *
     * @param int $invoiceId ID da fatura.
     * @param array $data Dados para atualizar a fatura.
     * @return array Resultado da requisição (sucesso ou erro).
     */
    public function updateInvoiceStatus(int $invoiceId, array $data)
    {
        // Faz a requisição PUT para atualizar o status de uma fatura
        $response = Http::withToken($this->token)
            ->put("{$this->baseUrl}/invoices/{$invoiceId}", $data);

        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        return [
            'success' => false,
            'message' => $response->json('message', 'Erro desconhecido'),
        ];
    }

    /**
     * Remove uma fatura específica.
     *
     * @param int $invoiceId ID da fatura.
     * @return array Resultado da requisição (sucesso ou erro).
     */
    public function deleteInvoice(int $invoiceId)
    {
        // Faz a requisição DELETE para remover uma fatura
        $response = Http::withToken($this->token)
            ->delete("{$this->baseUrl}/invoices/{$invoiceId}");

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Fatura removida com sucesso.',
            ];
        }

        return [
            'success' => false,
            'message' => $response->json('message', 'Erro desconhecido'),
        ];
    }

    public function testConnection()
{
    try {
        $response = Http::withToken($this->token)
            ->get("{$this->baseUrl}/ping"); // Supondo que haja um endpoint `/ping`

        if ($response->successful()) {
            return 'Conexão bem-sucedida.';
        }

        return 'Erro: ' . $response->status();
    } catch (\Exception $e) {
        return 'Erro ao conectar: ' . $e->getMessage();
    }
}

}
