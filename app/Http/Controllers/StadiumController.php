<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stadium;
use APP\Models\StadiumPlan;

class StadiumController extends Controller
{
    // Função para listar os estádios
    public function index()
    {
        $stadiums = Stadium::all();
        return view('stadiums.index', compact('stadiums'));
    }

    // Função para mostrar o formulário de criação de estádio
    public function create()
    {
        return view('stadiums.create');
    }

    // Função para guardar o novo estádio na base de dados
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'capacity' => 'required|integer',
            'num_stands' => 'required|integer|min:1|max:4',
        ]);

        $stadium = Stadium::create($validatedData);

        return redirect()->route('stands.configure', ['stadium_id' => $stadium->id])
                     ->with('success', 'Estádio criado com sucesso. Configure as bancadas e os lugares.');
    }

    // Função para mostrar o formulário de edição de estádio
    public function edit(Stadium $stadium)
    {
        return view('stadiums.edit', compact('stadium'));
    }

    // Função para atualizar o estádio na base de dados
    public function update(Request $request, Stadium $stadium)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'location' => 'required|max:255',
            'capacity' => 'required|integer',
            'num_stands' => 'required|integer|min:1|max:4',
        ]);

        $stadium->update($validatedData);

        return redirect()->route('stadiums.index')->with('success', 'Estádio atualizado com sucesso.');
    }

    // Função para eliminar o estádio da base de dados
    public function destroy(Stadium $stadium)
    {
        $stadium->delete();
        return redirect()->route('stadiums.index')->with('success', 'Estádio eliminado com sucesso.');
    }
    public function getStadiumPlans(Request $request)
    {
        $stadiumId = $request->stadium_id;

        // Obtém as plantas do estádio com base no ID do estádio
        $plans = StadiumPlan::where('stadium_id', $stadiumId)->get();

        return response()->json($plans);
    }
    public function getStadiumPlansByStadiumId(Request $request)
    {
        $stadiumId = $request->input('stadium_id');
        $plans = StadiumPlan::where('stadium_id', $stadiumId)->get();

        return response()->json($plans);
    }

}
