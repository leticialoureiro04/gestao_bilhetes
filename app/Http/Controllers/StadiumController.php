<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stadium;
use APP\Models\StadiumPlan;
use App\Models\Stand;
use App\Models\Seat;

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

    public function view(Request $request, $stadium_id)
{
    // Obter o estádio pelo ID
    $stadium = Stadium::findOrFail($stadium_id);

    // Validar o input antes de salvar
    $validated = $request->validate([
        'stands.*.name' => 'required|string|max:255',
        'stands.*.num_rows' => 'required|integer|min:1',
        'stands.*.seats_per_row' => 'required|integer|min:1',
        'stands.*.seat_types.1' => 'required|integer|min:0', // Lugares VIP
        'stands.*.seat_types.2' => 'required|integer|min:0', // Lugares Normais
    ]);

    // Verificar se stands foram enviados
    if (!is_array($validated['stands']) || empty($validated['stands'])) {
        return redirect()->back()->withErrors('Nenhuma bancada foi enviada.');
    }
    // Salvar as bancadas
    foreach ($validated['stands'] as $standData) {
        $stand= Stand::updateOrCreate(
            ['stadium_id' => $stadium->id, 'name' => $standData['name']],
            [
                'num_rows' => $standData['num_rows'],
                'seats_per_row' => $standData['seats_per_row'],
            ]
        );
    }
    // Criar os lugares para cada bancada
    for ($row = 1; $row <= $standData['num_rows']; $row++) {
        for ($seat = 1; $seat <= $standData['seats_per_row']; $seat++) {
            // Determinar o tipo de lugar (VIP ou Normal)
            $seatTypeId = $seat <= $standData['seat_types'][1] ? 1 : 2; // 1: VIP, 2: Normal

            Seat::updateOrCreate(
                [
                    'stand_id' => $stand->id,
                    'row_number' => $row,
                    'seat_number' => $seat,
                ],
                [
                    'seat_type_id' => $seatTypeId, // Associar o tipo de lugar
                    'status' => 'disponível', // Define o status como disponível por padrão
                ]
            );
        }
    }
    // Recarregar o estádio com as bancadas associadas
    $stadium->load('stands');

    // Retornar a view com o layout do estádio
    return view('stadiums.view', compact('stadium'));
}

}

