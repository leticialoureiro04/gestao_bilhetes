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
use Illuminate\Support\Facades\DB;

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

        Ticket::create([
            'game_id' => $request->game_id,
            'seat_id' => $seat->id,
            'user_id' => Auth::id(),
            'price' => $price,
            'status' => 'vendido',
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

    public function buyTickets(Request $request)
{
    $seatIds = $request->input('seat_ids');
    if (is_string($seatIds)) {
        $seatIds = json_decode($seatIds, true);
    }

    if (!$seatIds || count($seatIds) === 0) {
        return redirect()->route('tickets.index')->with('error', 'Nenhum lugar selecionado.');
    }

    try {
        $seats = Seat::whereIn('id', $seatIds)->where('status', 'disponível')->get();

        if ($seats->count() != count($seatIds)) {
            return redirect()->route('tickets.index')->with('error', 'Alguns lugares já foram vendidos ou não existem.');
        }

        $user = Auth::user();
        $totalAmount = 0;
        $ticketIds = [];
        $lines = []; // Para armazenar as linhas da fatura-recibo

        foreach ($seats as $seat) {
            $stand = $seat->stand;
            if (!$stand) {
                return redirect()->route('tickets.index')->with('error', 'Erro interno ao processar a compra.');
            }

            $game = Game::where('stadium_id', $stand->stadium_id)->first();

            if (!$game) {
                return redirect()->route('tickets.index')->with('error', 'Erro ao encontrar o jogo para o estádio.');
            }

            $ticket = Ticket::create([
                'game_id' => $game->id,
                'seat_id' => $seat->id,
                'user_id' => $user->id,
                'price' => $seat->seatType->price ?? 25,
                'status' => 'vendido',
            ]);

            $ticketIds[] = $ticket->id;
            $totalAmount += $ticket->price;

            // Atualizar o status do lugar para "vendido"
            $seat->update(['status' => 'vendido']);


            $lines[] = [
                'id' => $seat->seatType->product_id,
                'description' => $seat->seatType->name,
                'quantity' => 1,
                'price' => $seat->seatType->price ?? 25,
                'tax' => 1,
                'exemption' => 0,
                'discount' => 0,
                'retention' => 0,
            ];
        }


            $currentDate = now();
            $minDate = now()->createFromFormat('d/m/Y', '12/12/2024');

            // Verificar se a data atual é menor que a data mínima permitida
            if ($currentDate->lt($minDate)) {
                $currentDate = $minDate;
            }


            $invoiceData = [
                'client' => $user->id_gesfaturacao, // ID do cliente na API
                'serie' => 27, // Ajuste o valor da série conforme necessário
                'number' => 0,
                'date' => $currentDate->format('d/m/Y'), // Data ajustada
                'expiration' => now()->addDays(7)->format('d/m/Y'), // Data de vencimento
                'reference' => 'Fatura-Recibo ' . implode(',', $ticketIds),
                'dueDate' => 0,
                'coin' => 1,
                'discount' => 0,
                'observations' => 'Compra de bilhetes pelo sistema',
                'lines' => json_encode($lines), // Linhas da fatura-recibo
                'finalize' => 1, // Finalizar a fatura
                'payment' => 1, // Indica que a fatura foi paga
                'bank' => 0, // Não é um pagamento bancário
                'needsBank' => 0, // Não necessita de detalhes bancários
            ];


        // Enviar dados para a API
        $apiResponse = app(GesFaturacaoAPIController::class)->createReceiptInvoice($invoiceData);

        // Verificar resposta da API
        if ($apiResponse->original['success']) {
            $apiInvoiceId = $apiResponse->original['response']['id'];

            Invoice::create([
                'user_id' => $user->id,
                'ticket_id' => implode(',', $ticketIds),
                'title' => 'Fatura-Recibo para os bilhetes comprados',
                'total_amount' => $totalAmount,
                'saldo' => 0, // Fatura quitada
                'issue_date' => now(),
                'expiration' => now()->addDays(7),
                'status' => 'paga',
                'invoice_id' => $apiInvoiceId, // ID da fatura gerada na API
            ]);

            return redirect()->route('invoices.index')->with('success', 'Compra realizada com sucesso! Fatura-recibo gerada e enviada para a API.');
        } else {
            Log::error('Erro ao enviar fatura-recibo para a API GESFaturação.', ['response' => $apiResponse->original]);
            return redirect()->route('invoices.index')->with('error', 'Compra realizada, mas houve um erro ao enviar a fatura-recibo para a API.');
        }
    } catch (\Exception $e) {
        Log::error('Erro ao processar a compra de bilhetes:', ['message' => $e->getMessage()]);
        return redirect()->route('tickets.index')->with('error', 'Erro interno ao processar a compra.');
    }
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




