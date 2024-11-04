<?php

namespace App\Http\Controllers;

use App\Models\StadiumPlan;
use App\Models\Stadium;
use Illuminate\Http\Request;

class StadiumPlanController extends Controller
{
    public function index()
    {
        // Listar todas as plantas de estádio
        $stadiumPlans = StadiumPlan::with('stadium')->get(); // Carregar o estádio relacionado
        return view('stadium_plans.index', compact('stadiumPlans'));
    }

    public function create()
    {
        // Obter todos os estádios para popular o dropdown
        $stadiums = Stadium::all();
        return view('stadium_plans.create', compact('stadiums'));
    }

    public function store(Request $request)
    {
        // Valida os dados do formulário
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'name' => 'required',
            'description' => 'nullable',
        ]);

        // Cria uma nova planta de estádio
        StadiumPlan::create($request->all());

        return redirect()->route('stadium_plans.index')->with('success', 'Planta de Estádio criada com sucesso!');
    }

    public function edit(StadiumPlan $stadiumPlan)
    {
        // Obter todos os estádios para popular o dropdown
        $stadiums = Stadium::all();
        return view('stadium_plans.edit', compact('stadiumPlan', 'stadiums'));
    }

    public function update(Request $request, StadiumPlan $stadiumPlan)
    {
        // Valida os dados do formulário
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'name' => 'required',
            'description' => 'nullable',
        ]);

        // Atualiza a planta de estádio
        $stadiumPlan->update($request->all());

        return redirect()->route('stadium_plans.index')->with('success', 'Planta de Estádio atualizada com sucesso!');
    }
    public function getStadiumPlansByStadiumId(Request $request)
    {
        $stadiumId = $request->input('stadium_id');

        // Procurar todas as plantas associadas ao estádio selecionado
        $plans = StadiumPlan::where('stadium_id', $stadiumId)->get();

        return response()->json($plans);
    }
}


