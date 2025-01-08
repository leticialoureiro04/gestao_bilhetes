<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Stand;
use App\Models\StadiumPlan;
use App\Models\SeatType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class SeatController extends Controller
{
    public function index()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $seats = Seat::with(['stand.stadium', 'stadiumPlan', 'seatType'])->get();
        return view('seats.index', compact('seats'));
    }

    public function create()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadiumPlans = StadiumPlan::with('stadium')->get();
        $seatTypes = SeatType::all();
        $stands = Stand::all();
        return view('seats.create', compact('stadiumPlans', 'seatTypes', 'stands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stand_id' => 'required|exists:stands,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'row_number' => 'required|integer|min:1',
            'seat_number' => 'required|integer|min:1',
            'status' => 'required|in:disponível,reservado,vendido',
        ]);

        // Obter o stand e o estádio associado
        $stand = Stand::with('stadium')->findOrFail($request->stand_id);

        if (!$stand->stadium) {
            return redirect()->route('seats.index')->with('error', 'Estádio não encontrado para o stand selecionado.');
        }

        // Criar o assento com o campo stadium_id atribuído
        Seat::create([
            'stadium_id' => $stand->stadium->id,
            'stand_id' => $request->stand_id,
            'seat_type_id' => $request->seat_type_id,
            'row_number' => $request->row_number,
            'seat_number' => $request->seat_number,
            'status' => $request->status,
        ]);

        return redirect()->route('seats.index')->with('success', 'Lugar criado com sucesso!');
    }

    public function edit(Seat $seat)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadiumPlans = StadiumPlan::all();
        $seatTypes = SeatType::all();
        $stands = Stand::all();
        return view('seats.edit', compact('seat', 'stadiumPlans', 'seatTypes', 'stands'));
    }

    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'stand_id' => 'required|exists:stands,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'row_number' => 'required|integer|min:1',
            'seat_number' => 'required|integer|min:1',
            'status' => 'required|in:disponível,reservado,vendido',
        ]);

        // Obter o stand e o estádio associado
        $stand = Stand::with('stadium')->findOrFail($request->stand_id);

        if (!$stand->stadium) {
            return redirect()->route('seats.index')->with('error', 'Estádio não encontrado para o stand selecionado.');
        }

        // Atualizar o assento com o campo stadium_id atribuído
        $seat->update([
            'stadium_id' => $stand->stadium->id,
            'stand_id' => $request->stand_id,
            'seat_type_id' => $request->seat_type_id,
            'row_number' => $request->row_number,
            'seat_number' => $request->seat_number,
            'status' => $request->status,
        ]);

        return redirect()->route('seats.index')->with('success', 'Lugar atualizado com sucesso!');
    }
    public function destroy(Seat $seat)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $seat->delete();
        return redirect()->route('seats.index')->with('success', 'Lugar eliminado com sucesso!');
    }

    public function getAvailableSeats($stadium_id)
    {
        $seats = Seat::where('status', 'disponível')
                     ->where('stadium_id', $stadium_id)
                     ->with('seatType')
                     ->get()
                     ->map(function($seat) {
                         return [
                             'id' => $seat->id,
                             'stand' => $seat->stand->name ?? null,
                             'tipo' => $seat->seatType->name,
                             'price' => $seat->seatType->price
                         ];
                     });

        return response()->json(['seats' => $seats]);
    }

    public function getSeatsByStadium($stadium_id)
    {
        $seats = Seat::where('stadium_id', $stadium_id)
                     ->where('status', 'disponível')
                     ->with(['seatType', 'stand']) // Inclui stands
                     ->get();

        return response()->json($seats);
    }
}

