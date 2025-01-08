<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use App\Models\Ticket;

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
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => [
            "Authorization: $token",
            'Cookie: PHPSESSID=1d360bcc6974427c545f9bd8c85e2b64'
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

public function addClient($userId)
{
    // Obter o utilizador da base de dados
    $user = User::find($userId);

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'Usuário não encontrado.'], 404);
    }

    // Configurar a URL e o token
    $url = 'https://devipvc.gesfaturacao.pt/api/v1.0.3/client';
    $token = env('TOKEN');
    $data = http_build_query([
        'country' => 'PT',
        'vatNumber' => '999999990',
        'name' => $user->name,
        'email' => $user->email,
        'address' => '',
        'postalCode' => '',
        'region' => '',
        'city' => '',
        'website' => '',
        'mobile' => '',
        'telephone' => '',
        'fax' => '',
        'representativeName' => '',
        'representativeEmail' => '',
        'representativeMobile' => '',
        'representativeTelephone' => '',
        'paymentMethod' => '',
        'paymentCondition' => '',
        'discount' => '',
        'accountType' => '',
        'internalCode' => '',
        'id' => '',
        'comments' => '',
    ]);

    try {
        // Inicializar cURL
        $curl = curl_init();

        // Configurar o cURL
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

        // Executar o cURL
        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        // Verificar erros no cURL
        if ($error) {
            Log::error('Erro no cURL: ' . $error);
            return response()->json(['success' => false, 'message' => 'Erro no cURL.', 'error' => $error], 500);
        }

        // Converter a resposta para JSON
        $responseData = json_decode($response, true);

        // Log da resposta
        Log::info('Add Client Response: ', ['response' => $responseData]);

        // Verificar se a API retornou sucesso
        if (isset($responseData['id'])) {
            $user->id_gesfaturacao = $responseData['id'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Cliente adicionado com sucesso.',
                'id_gesfaturacao' => $responseData['id'],
            ]);
        } else {
            Log::error('Erro na resposta da API:', ['response' => $responseData]);
            return response()->json(['success' => false, 'message' => 'Erro na resposta da API.', 'response' => $responseData], 500);
        }
    } catch (\Exception $e) {
        Log::error('Erro no addClient: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Erro ao adicionar cliente.', 'error' => $e->getMessage()], 500);
    }
}

/**
 * Cria uma fatura-recibo na API GESFaturação.
 *
 * @param array $invoiceData Dados necessários para criar a fatura-recibo.
 * @return \Illuminate\Http\JsonResponse Resposta da API
 */
public function createReceiptInvoice(array $invoiceData)
{
    $url = config('services.gesfaturacao.url') . '/' . config('services.gesfaturacao.version') . '/sales/receipt-invoice';
    $token = env('TOKEN');

    // Verificar se o campo 'client' foi fornecido
    if (!isset($invoiceData['client']) || empty($invoiceData['client'])) {
        Log::error('O campo "client" é obrigatório para criar uma fatura-recibo.');
        return response()->json(['success' => false, 'message' => 'O campo "client" é obrigatório para criar uma fatura-recibo.'], 400);
    }

    // Adicionar os campos obrigatórios para fatura-recibo
    $invoiceData['payment'] = 1; // Indica que a fatura foi paga
    $invoiceData['bank'] = 0; // Indica que não foi feito via banco
    $invoiceData['needsBank'] = 0; // Não necessita de detalhes bancários

    // Configurar o cURL para fazer a requisição
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => http_build_query($invoiceData),
        CURLOPT_HTTPHEADER => [
            "Authorization: $token",
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);

    // Executa a requisição e captura a resposta
    $response = curl_exec($curl);
    $error = curl_error($curl); // Captura erros do cURL
    curl_close($curl); // Fecha a sessão do cURL

    // Se ocorrer um erro no cURL
    if ($error) {
        Log::error('Erro no cURL ao criar a fatura-recibo: ' . $error);
        return response()->json(['success' => false, 'message' => 'Erro ao criar a fatura-recibo.', 'error' => $error], 500);
    }

    // Converte a resposta para JSON
    $responseData = json_decode($response, true);

    // Verifica se a fatura-recibo foi criada com sucesso
    if (isset($responseData['id']) && isset($responseData['document'])) {
        Log::info('Fatura-recibo criada com sucesso.', ['response' => $responseData]);
        return response()->json([
            'success' => true,
            'message' => 'Fatura-recibo criada com sucesso.',
            'response' => $responseData,
        ]);
    }

    // Caso ocorra erro na criação da fatura-recibo
    Log::error('Erro ao criar a fatura-recibo.', ['response' => $responseData]);
    return response()->json([
        'success' => false,
        'message' => 'Erro ao criar a fatura-recibo.',
        'response' => $responseData,
    ], 400);
}


public function payInvoice($invoiceId)
{
    $url = config('services.gesfaturacao.url') . '/' . config('services.gesfaturacao.version') . "/sales/invoice/{$invoiceId}/pay";
    $token = env('TOKEN');

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_HTTPHEADER => [
            "Authorization: $token",
        ],
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        Log::error('Erro no cURL ao pagar fatura: ' . $error);
        return response()->json(['success' => false, 'message' => 'Erro ao processar o pagamento.', 'error' => $error], 500);
    }

    $responseData = json_decode($response, true);

    if (isset($responseData['success']) && $responseData['success']) {
        Log::info('Fatura paga com sucesso.', ['response' => $responseData]);
        return response()->json(['success' => true, 'message' => 'Fatura paga com sucesso.', 'response' => $responseData]);
    }

    Log::error('Erro ao pagar fatura.', ['response' => $responseData]);
    return response()->json([
        'success' => false,
        'message' => 'Erro ao pagar fatura.',
        'response' => $responseData
    ], 400);
}


}


