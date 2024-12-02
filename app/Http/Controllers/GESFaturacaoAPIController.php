<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http; // Facade para requisições HTTP
use Illuminate\Support\Facades\Log;  // Para logging
use Illuminate\Support\Facades\Config;
use App\Models\User;

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
public function validateToken()
{
    $url = config('services.gesfaturacao.url') . '/' . config('services.gesfaturacao.version') . '/validate-token';
    $token = env('TOKEN');
    Log::info($token);
    // Configurar o cURL para fazer a requisição
    $curl = curl_init();

    // Configuração do cURL com os parâmetros necessários
    curl_setopt_array($curl, [
        CURLOPT_URL => $url, // URL para o endpoint
        CURLOPT_RETURNTRANSFER => true, // Retorna o resultado em vez de exibir
        CURLOPT_ENCODING => '', // Sem encoding específico
        CURLOPT_MAXREDIRS => 10, // Máximo de redirecionamentos
        CURLOPT_TIMEOUT => 0, // Sem limite de tempo
        CURLOPT_FOLLOWLOCATION => true, // Seguir redirecionamentos
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Versão do HTTP
        CURLOPT_CUSTOMREQUEST => 'POST', // Método POST
        CURLOPT_HTTPHEADER => [
            "Authorization: $token", // Cabeçalho com o token
            'Cookie: PHPSESSID=1d360bcc6974427c545f9bd8c85e2b64' // Cookie, se necessário
        ],
    ]);

    // Executa a requisição e captura a resposta
    $response = curl_exec($curl);
    $error = curl_error($curl); // Captura erros do cURL

    // Fecha a sessão do cURL
    curl_close($curl);

    // Se ocorrer um erro no cURL
    if ($error) {
        Log::error('Erro no cURL ao validar o token: ' . $error);
        return response()->json(['success' => false, 'message' => 'Erro ao validar o token.', 'error' => $error], 500);
    }

    // Converte a resposta para JSON
    $responseData = json_decode($response, true);

    // Verifica se o token é válido
    if (isset($responseData['status']) && $responseData['status'] === 'ok') {
        return response()->json(['success' => true, 'message' => 'Token válido.', 'response' => $responseData]);
    }

    // Caso o token seja inválido ou tenha expirado
    return response()->json([
        'success' => false,
        'message' => 'Token inválido ou expirado.',
        'response' => $responseData
    ], 401);
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

    public function addClient($userId)
{
    // Obter o utilizador da base de dados
    $user = User::find($userId);

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Usuário não encontrado.'], 404);
    }

    // Configurar a URL e o token
    $url = config('services.gesfaturacao.url') . '/' . config('services.gesfaturacao.version') . '/client';
    $token = env('TOKEN'); // Buscar o token diretamente do .env

    // Dados para enviar na requisição
    $data = http_build_query([
        'name' => $user->name,
        'email' => $user->email,
        'vatNumber' => $user->vatNumber,
        'country' => $user->country,
    ]);

    try {
        // Inicializar cURL
        $curl = curl_init();

        // Configurar as opções do cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Content-Language: en',
                'Content-Type: application/x-www-form-urlencoded',
                "Authorization: $token",
            ],
        ]);

        // Executar a requisição
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Código HTTP da resposta
        $error = curl_error($curl);

        // Fechar a conexão cURL
        curl_close($curl);

        // Verificar erros na requisição
        if ($error) {
            Log::error('Erro no cURL: ' . $error);
            return response()->json(['success' => false, 'message' => 'Erro na requisição cURL.', 'error' => $error], 500);
        }

        // Converter a resposta para JSON
        $responseData = json_decode($response, true);

        // Log para depuração
        Log::info('Add Client API Response: ', ['response' => $responseData]);

        // Verificar o sucesso da resposta
        if ($httpCode === 200 && isset($responseData['id'])) {
            // Atualizar o `id_gesfaturacao` no banco de dados
            $user->id_gesfaturacao = $responseData['id'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cliente adicionado com sucesso.',
                'id_gesfaturacao' => $responseData['id'],
            ]);
        } else {
            // Tratar erros retornados pela API
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar cliente na API.',
                'response' => $responseData,
            ], $httpCode);
        }
    } catch (\Exception $e) {
        // Capturar exceções e logar
        Log::error('Erro ao adicionar cliente na API: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Erro ao adicionar cliente na API.', 'error' => $e->getMessage()], 500);
    }
}


}
