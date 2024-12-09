<?php

namespace App\Http\Controllers;

use App\Models\StadiumPlan;
use App\Models\Stadium;
use App\Models\Seat;
use Illuminate\Http\Request;

class StadiumPlanController extends Controller
{
    public function index()
    {
        $stadiumPlans = StadiumPlan::with('stadium')->get();
        return view('stadium_plans.index', compact('stadiumPlans'));
    }

    public function create()
    {
        $stadiums = Stadium::all();
        return view('stadium_plans.create', compact('stadiums'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'name' => 'required',
            'description' => 'nullable',
        ]);

        StadiumPlan::create($request->all());

        return redirect()->route('stadium_plans.index')->with('success', 'Planta de Estádio criada com sucesso!');
    }

    public function edit(StadiumPlan $stadiumPlan)
    {
        $stadiums = Stadium::all();
        return view('stadium_plans.edit', compact('stadiumPlan', 'stadiums'));
    }

    public function update(Request $request, StadiumPlan $stadiumPlan)
    {
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $stadiumPlan->update($request->all());

        return redirect()->route('stadium_plans.index')->with('success', 'Planta de Estádio atualizada com sucesso!');
    }

    public function getSeatsByStadiumPlan($stadiumPlanId)
    {
        $seats = Seat::where('stadium_plan_id', $stadiumPlanId)
                      ->where('status', 'disponível')
                      ->get();
        return response()->json($seats);
    }

    public function show($id)
    {
        $stadiumPlan = StadiumPlan::with('stadium')->findOrFail($id);
        $seats = Seat::where('stadium_plan_id', $id)->get();

        return view('stadium_plans.stadium_plan', compact('stadiumPlan', 'seats'));
    }

    public function viewSeats($id)
    {
        $stadiumPlan = StadiumPlan::findOrFail($id);
        $seats = Seat::where('stadium_plan_id', $id)->get();

        return view('stadium_plans.seats', compact('stadiumPlan', 'seats'));
    }

    public function choosePlan($game_id)
    {
        $stadiumPlans = StadiumPlan::where('stadium_id', function ($query) use ($game_id) {
            $query->select('stadium_id')
                  ->from('games')
                  ->where('id', $game_id)
                  ->limit(1);
        })->get();

        return view('stadium_plans.choose', compact('stadiumPlans', 'game_id'));
    }

    public function getStadiumPlansByStadiumId(Request $request)
    {
        $stadiumId = $request->input('stadium_id');
        $plans = StadiumPlan::where('stadium_id', $stadiumId)->get();
        return response()->json($plans);
    }
}
