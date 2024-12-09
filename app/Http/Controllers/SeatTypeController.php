<?php

namespace App\Http\Controllers;

use App\Models\SeatType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class SeatTypeController extends Controller
{
    public function index()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $seatTypes = SeatType::all();
        return view('seat_types.index', compact('seatTypes'));
    }

    public function create()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

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
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

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
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $seatType->delete();
        return redirect()->route('seat_types.index')->with('success', 'Tipo de Lugar eliminado com sucesso!');
    }
}
