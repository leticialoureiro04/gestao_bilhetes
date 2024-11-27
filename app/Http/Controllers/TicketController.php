<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Game;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Stadium;
use App\Models\StadiumPlan;
use App\Models\SeatType;
use Illuminate\Support\Facades\Log;
use App\Services\GesFaturacaoService;
use App\Models\Invoice;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class TicketController extends Controller
{
    public function index()
    {

        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $user = Auth::user();

        if ($user->roles->contains('name', 'admin')) {
            $tickets = Ticket::with(['game.stadium', 'seat.stadiumPlan', 'seat.seatType', 'user', 'invoice'])->get();
        } else {
            $tickets = Ticket::where('user_id', $user->id)
                             ->with(['game.stadium', 'seat.stadiumPlan', 'seat.seatType'])
                             ->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create(Request $request)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $games = Game::with('stadium')->get();
        $stadiums = Stadium::all();
        $seatTypes = SeatType::all();
        $stadium = $request->has('game_id') ? Game::find($request->game_id)->stadium : null;
        $seats = collect();

        return view('tickets.create', compact('games', 'stadiums', 'seatTypes', 'seats', 'stadium'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'seat_id' => 'required|exists:seats,id|unique:tickets,seat_id',
            'seat_type_id' => 'required|exists:seat_types,id',
        ]);

        $seat = Seat::findOrFail($request->seat_id);
        $price = $seat->seatType->price ?? 25;

        $ticket =Ticket::create([
            'game_id' => $request->game_id,
            'seat_id' => $seat->id,
            'user_id' => Auth::id(),
            'price' => $price,
            'status' => 'vendido',
        ]);

        $invoice = Invoice::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'title' => 'Fatura para o jogo ' . $ticket->game_id,
            'total_amount' => $price,
            'saldo' => $price,
            'issue_date' => now(),
            'expiration' => now()->addDays(7),
            'status' => 'pendente',
        ]);

        $seat->update(['status' => 'vendido']);

        return redirect()->route('tickets.index')->with('success', 'Bilhete emitido com sucesso!');
    }

    public function edit(Ticket $ticket)
    {

        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $games = Game::all();
        $seats = Seat::where('status', 'disponível')->orWhere('id', $ticket->seat_id)->get();
        return view('tickets.edit', compact('ticket', 'games', 'seats'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'seat_id' => 'required|exists:seats,id',
            'user_id' => 'required|exists:users,id',
            'price' => 'required|numeric',
            'status' => 'required|in:disponível,vendido',
        ]);

        if ($ticket->seat_id != $request->seat_id) {
            $previousSeat = Seat::find($ticket->seat_id);
            $previousSeat->update(['status' => 'disponível']);
        }

        $newSeat = Seat::findOrFail($request->seat_id);
        $newSeat->update(['status' => $request->status]);

        $ticket->update([
            'game_id' => $request->game_id,
            'seat_id' => $newSeat->id,
            'user_id' => $request->user_id,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        $invoice = $ticket->invoice; // Obter a fatura associada
        if ($invoice) {
            $invoice->update([
            'total_amount' => $request->price, // Atualize o valor total
            'saldo' => $request->price, // Atualize o saldo
            'status' => $request->status === 'vendido' ? 'pendente' : 'cancelada', // Atualizar status com base no ticket
        ]);
}

        return redirect()->route('tickets.index')->with('success', 'Bilhete atualizado com sucesso!');
    }

    public function destroy(Ticket $ticket)
    {
        $seat = Seat::find($ticket->seat_id);
        if ($seat) {
            $seat->update(['status' => 'disponível']);
        }

        // Cancelar a fatura associada
        $invoice = $ticket->invoice;
        if ($invoice) {
            $invoice->update(['status' => 'cancelada']);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Bilhete cancelado com sucesso!');
    }

    public function buyTickets(Request $request,GesFaturacaoService $gesFaturacaoService)
{
    $seatIds = $request->input('seat_ids');
    if (is_string($seatIds)) {
        $seatIds = json_decode($seatIds, true); // Decodificar se for string JSON
    }

    Log::info('IDs de assentos recebidos:', ['seatIds' => $seatIds]);


    if (!$seatIds || count($seatIds) === 0) {
        Log::warning('Nenhum lugar selecionado ou IDs inválidos.');
        return response()->json(['message' => 'Nenhum lugar selecionado.'], 400);
    }

    try {
        $seats = Seat::whereIn('id', $seatIds)->where('status', 'disponível')->get();

        Log::info('Lugares encontrados:', ['seats' => $seats]);

        if ($seats->count() != count($seatIds)) {
            Log::warning('Alguns lugares já foram vendidos ou não existem.');
            return response()->json(['message' => 'Alguns lugares já foram vendidos ou não existem.'], 400);
        }

        foreach ($seats as $seat) {
            // Use a tabela stands em vez de stadium_plans
            $stand = $seat->stand;
            if (!$stand) {
                Log::error('Erro: Stand não encontrado para o lugar.', ['seat_id' => $seat->id]);
                return response()->json(['message' => 'Erro interno ao processar a compra.'], 500);
            }

            $game = Game::where('stadium_id', $stand->stadium_id)->first();

            if (!$game) {
                Log::error('Erro: Não foi possível encontrar o jogo associado ao estádio.', ['seat_id' => $seat->id]);
                return response()->json(['message' => 'Erro ao encontrar o jogo para o estádio.'], 500);
            }

            Log::info('Jogo encontrado para o lugar:', ['game_id' => $game->id]);

            $ticket =Ticket::create([
                'game_id' => $game->id,
                'seat_id' => $seat->id,
                'user_id' => Auth::id(),
                'price' => $seat->seatType->price ?? 25,
                'status' => 'vendido',
            ]);

            $seat->update(['status' => 'vendido']);

             // Criar a fatura no banco de dados
             $invoice = Invoice::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(), // ID do usuário autenticado
                'title' => 'Fatura para o jogo ' . $game->id, // Título descritivo
                'total_amount' => $ticket->price,
                'saldo' => $ticket->price, // Inicialmente, o saldo é igual ao valor total
                'issue_date' => now(), // Data de emissão da fatura
                'expiration' => now()->addDays(7), // Definir a data de vencimento como 7 dias a partir de hoje
                'status' => 'pendente',
            ]);

            Log::info('Fatura criada localmente.', ['invoice_id' => $invoice->id]);

            Log::error('Erro ao criar fatura no método buyTickets.', [
                'ticket_id' => $ticket->id,
                'seat_id' => $seat->id,
                'user_id' => Auth::id(),
            ]);


            // Enviar a fatura para a API GesFaturação
            $response = $gesFaturacaoService->createInvoice([
                'ticket_id' => $invoice->ticket_id,
                'total_amount' => $invoice->total_amount,
                'status' => $invoice->status,
            ]);

            if (!$response['success']) {
                Log::warning('Fatura criada localmente, mas não foi enviada para a API.', ['invoice_id' => $invoice->id]);
                return response()->json(['message' => 'Erro ao enviar fatura para a API.'], 500);
            }

            Log::info('Fatura enviada com sucesso para a API.', ['invoice_id' => $invoice->id]);
        }

        Log::info('Compra concluída com sucesso.');
        return response()->json(['success' => true, 'message' => 'Compra realizada com sucesso!', 'redirect' => route('tickets.index')]);
    } catch (\Exception $e) {
        Log::error('Erro ao processar a compra de bilhetes:', ['message' => $e->getMessage()]);
        return response()->json(['message' => 'Erro interno ao processar a compra.'], 500);
    }
}

private function sendInvoiceToGesFaturacao($invoice)
{
    $url = 'https://dev.gesfaturacao.pt/api/invoices'; // Endpoint da API

    $data = [
        'ticket_id' => $invoice->ticket_id,
        'total_amount' => $invoice->total_amount,
        'status' => $invoice->status,
    ];

    // Iniciar cURL
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . env('GESFATURACAO_TOKEN'), // Certifique-se de que o token é válido
            'Content-Type: application/json',
        ],
    ]);

    // Executar cURL
    $response = curl_exec($ch);

    // Fechar a sessão cURL
    curl_close($ch);

    // Verificar se a requisição foi bem-sucedida
    if ($response) {
        return ['success' => true, 'data' => json_decode($response)];
    }

    return ['success' => false, 'message' => 'Erro ao enviar fatura para a API.'];
}

    public function getAvailableSeats(Request $request)
    {
        $seats = Seat::where('stadium_id', $request->stadium_id)
                     ->where('status', 'disponível')
                     ->get();

        return response()->json($seats);
    }

    public function getSeatsByType(Request $request)
    {
        $stadiumId = $request->input('stadium_id');
        $seatTypeId = $request->input('seat_type_id');

        $seats = Seat::where('stadium_id', $stadiumId)
                     ->where('seat_type_id', $seatTypeId)
                     ->where('status', 'disponível')
                     ->with('stadiumPlan')
                     ->get();

        return response()->json($seats);
    }

    public function getSeatsByTypeAndPlan(Request $request)
    {
        $stadiumPlanId = $request->input('stadium_plan_id');
        $seatTypeId = $request->input('seat_type_id');

        $seats = Seat::where('stadium_plan_id', $stadiumPlanId)
                     ->where('seat_type_id', $seatTypeId)
                     ->where('status', 'disponível')
                     ->get();

        return response()->json($seats);
    }
}




