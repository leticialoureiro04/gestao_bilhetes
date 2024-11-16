<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Stand;
use App\Models\StadiumPlan;
use App\Models\SeatType;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index()
    {
        $seats = Seat::with(['stand.stadium', 'stadiumPlan', 'seatType'])->get();
        return view('seats.index', compact('seats'));
    }

    public function create()
    {
        $stadiumPlans = StadiumPlan::with('stadium')->get();
        $seatTypes = SeatType::all();
        $stands = Stand::all(); // Adiciona stands aqui
        return view('seats.create', compact('stadiumPlans', 'seatTypes', 'stands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stadium_plan_id' => 'required|exists:stadium_plans,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'stand_id' => 'required|exists:stands,id',
            'row_number' => 'required|integer|min:1',
            'seat_number' => 'required|integer|min:1',
            'status' => 'required|in:disponível,reservado,vendido',
        ]);

        Seat::create($request->all());

        return redirect()->route('seats.index')->with('success', 'Lugar criado com sucesso!');
    }

    public function edit(Seat $seat)
    {
        $stadiumPlans = StadiumPlan::all();
        $seatTypes = SeatType::all();
        $stands = Stand::all();
        return view('seats.edit', compact('seat', 'stadiumPlans', 'seatTypes', 'stands'));
    }

    public function update(Request $request, Seat $seat)
    {
        $request->validate([
            'stadium_plan_id' => 'required|exists:stadium_plans,id',
            'seat_type_id' => 'required|exists:seat_types,id',
            'stand_id' => 'required|exists:stands,id',
            'row_number' => 'required|integer|min:1',
            'seat_number' => 'required|integer|min:1',
            'status' => 'required|in:disponível,reservado,vendido',
        ]);

        $seat->update($request->all());

        return redirect()->route('seats.index')->with('success', 'Lugar atualizado com sucesso!');
    }

    public function destroy(Seat $seat)
    {
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
                         'planta' => $seat->planta,
                         'tipo' => $seat->seatType->name,
                         'price' => $seat->seatType->price
                     ];
                 });

    return response()->json(['seats' => $seats]);
}

public function getSeatsByStadium($stadium_id)
{
    $seats = Seat::where('stadium_plan_id', $stadium_id)
                 ->where('status', 'disponível')
                 ->with(['seatType', 'stand']) // Inclui stands
                 ->get();

    return response()->json($seats);
}

}
