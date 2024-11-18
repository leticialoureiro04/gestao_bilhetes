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

class TicketController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->roles->contains('name', 'admin')) {
            $tickets = Ticket::with(['game.stadium', 'seat.stadiumPlan', 'seat.seatType', 'user'])->get();
        } else {
            $tickets = Ticket::where('user_id', $user->id)
                             ->with(['game.stadium', 'seat.stadiumPlan', 'seat.seatType'])
                             ->get();
        }

        return view('tickets.index', compact('tickets'));
    }

    public function create(Request $request)
    {
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

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Bilhete cancelado com sucesso!');
    }

    public function buyTickets(Request $request)
{
    $seatIds = json_decode($request->input('seat_ids'));

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
            if (is_null($seat->stadiumPlan)) {
                Log::error('Erro: stadiumPlan não encontrado para o lugar.', ['seat_id' => $seat->id]);
                return response()->json(['message' => 'Erro interno ao processar a compra.'], 500);
            }

            $game = Game::where('stadium_id', $seat->stadiumPlan->stadium_id)->first();

            if (!$game) {
                Log::error('Erro: Não foi possível encontrar o jogo associado ao estádio.', ['seat_id' => $seat->id]);
                return response()->json(['message' => 'Erro ao encontrar o jogo para o estádio.'], 500);
            }

            Log::info('Jogo encontrado para o lugar:', ['game_id' => $game->id]);

            Ticket::create([
                'game_id' => $game->id,
                'seat_id' => $seat->id,
                'user_id' => Auth::id(),
                'price' => $seat->seatType->price ?? 25,
                'status' => 'vendido',
            ]);

            $seat->update(['status' => 'vendido']);
        }

        Log::info('Compra concluída com sucesso.');
        return response()->json(['success' => true, 'message' => 'Compra realizada com sucesso!', 'redirect' => route('tickets.index')]);
    } catch (\Exception $e) {
        Log::error('Erro ao processar a compra de bilhetes:', ['message' => $e->getMessage()]);
        return response()->json(['message' => 'Erro interno ao processar a compra.'], 500);
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




