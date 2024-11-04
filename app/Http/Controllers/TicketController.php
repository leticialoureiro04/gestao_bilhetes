<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Game;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Stadium;
use App\Models\SeatType;

class TicketController extends Controller
{


    public function index()
    {
        $user = Auth::user();

        // Se o utilizador tiver o papel de "admin", obtém todos os bilhetes
        if ($user->roles->contains('name', 'admin')) {
            $tickets = Ticket::with('game', 'seat', 'user')->get();
        } else {
            // Caso contrário, obtém apenas os bilhetes pertencentes ao utilizadorautenticado
            $tickets = Ticket::with('game', 'seat', 'user')
                             ->where('user_id', $user->id)
                             ->get();
        }

        return view('tickets.index', compact('tickets'));
    }




    public function create(Request $request)
    {
        $games = Game::with('stadium')->get();
        $stadiums = Stadium::all();
        $seatTypes = SeatType::all(); // Carregar todos os tipos de lugares
        $stadium = $request->has('game_id') ? Game::find($request->game_id)->stadium : null;

        $seats = collect(); // Inicializar com uma coleção vazia

        return view('tickets.create', compact('games', 'stadiums', 'seatTypes', 'seats', 'stadium'));
    }





    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'seat_id' => 'required|exists:seats,id|unique:tickets,seat_id',
            'seat_type_id' => 'required|exists:seat_types,id',
        ]);

        // Obter o assento selecionado
        $seat = Seat::findOrFail($request->seat_id);

        // Criar o bilhete
        Ticket::create([
            'game_id' => $request->game_id,
            'seat_id' => $seat->id,
            'user_id' => Auth::id(),
            'price' => $seat->seatType->price,
            'status' => 'vendido',
        ]);

        // Atualizar o status do lugar para "vendido"
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

    // Reverter o status do assento anterior para "disponível" se o assento foi alterado
    if ($ticket->seat_id != $request->seat_id) {
        $previousSeat = Seat::find($ticket->seat_id);
        $previousSeat->update(['status' => 'disponível']);
    }

    // Atualizar o novo assento para o status atual do bilhete
    $newSeat = Seat::findOrFail($request->seat_id);
    $newSeat->update(['status' => $request->status]);

    // Atualizar os dados do bilhete
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
    // Atualizar o status do assento para "disponível" antes de remover o bilhete
    $seat = Seat::find($ticket->seat_id);
    if ($seat) {
        $seat->update(['status' => 'disponível']);
    }

    // Remover o bilhete
    $ticket->delete();

    return redirect()->route('tickets.index')->with('success', 'Bilhete cancelado com sucesso!');
}

    public function buyTicket(Request $request, Game $game)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id',
        ]);

        $seat = Seat::findOrFail($request->seat_id);
        $userId = Auth::id();
        $price = $seat->seatType->price;

        Ticket::create([
            'game_id' => $game->id,
            'seat_id' => $seat->id,
            'user_id' => $userId,
            'price' => $price,
            'status' => 'vendido',
        ]);

        // Atualizar o status do lugar para "vendido"
        $seat->update(['status' => 'vendido']);

        return redirect()->route('tickets.index')->with('success', 'Bilhete comprado com sucesso!');
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


