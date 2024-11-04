<?php

namespace App\Http\Controllers;

use App\Models\SeatType;
use Illuminate\Http\Request;

class SeatTypeController extends Controller
{
    public function index()
    {
        $seatTypes = SeatType::all();
        return view('seat_types.index', compact('seatTypes'));
    }

    public function create()
    {
        return view('seat_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric', // Validação para o campo de preço
        ]);

        SeatType::create($request->all());

        return redirect()->route('seat_types.index')->with('success', 'Tipo de Lugar criado com sucesso!');
    }

    public function edit(SeatType $seatType)
    {
        return view('seat_types.edit', compact('seatType'));
    }

    public function update(Request $request, SeatType $seatType)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric', // Validação para o campo de preço
        ]);

        $seatType->update($request->all());

        return redirect()->route('seat_types.index')->with('success', 'Tipo de Lugar atualizado com sucesso!');
    }

    public function destroy(SeatType $seatType)
    {
        $seatType->delete();
        return redirect()->route('seat_types.index')->with('success', 'Tipo de Lugar eliminado com sucesso!');
    }
}
