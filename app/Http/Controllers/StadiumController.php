<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stadium;
use APP\Models\StadiumPlan;
use App\Models\Stand;
use App\Models\Seat;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class StadiumController extends Controller
{
    // Função para listar os estádios
    public function index()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadiums = Stadium::all();
        return view('stadiums.index', compact('stadiums'));
    }

    // Função para mostrar o formulário de criação de estádio
    public function create()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

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
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

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
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadium->delete();
        return redirect()->route('stadiums.index')->with('success', 'Estádio eliminado com sucesso.');
    }
    public function getStadiumPlans(Request $request)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadiumId = $request->stadium_id;

        // Obtém as plantas do estádio com base no ID do estádio
        $plans = StadiumPlan::where('stadium_id', $stadiumId)->get();

        return response()->json($plans);
    }
    public function getStadiumPlansByStadiumId(Request $request)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadiumId = $request->input('stadium_id');
        $plans = StadiumPlan::where('stadium_id', $stadiumId)->get();

        return response()->json($plans);
    }

    public function view(Request $request, $stadium_id)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // Obter o estádio pelo ID
        $stadium = Stadium::findOrFail($stadium_id);

        // Validar os dados recebidos
        $validated = $request->validate([
            'stands' => 'required|array', // Certifique-se de que 'stands' é um array
            'stands.*.name' => 'required|string|max:255',
            'stands.*.num_rows' => 'required|integer|min:1',
            'stands.*.seats_per_row' => 'required|integer|min:1',
        ]);

        // Iterar sobre as bancadas e salvar cada uma
        foreach ($validated['stands'] as $standData) {
            // Criar ou atualizar a bancada
            $stand = Stand::updateOrCreate(
                [
                    'stadium_id' => $stadium->id,
                    'name' => $standData['name'],
                ],
                [
                    'num_rows' => $standData['num_rows'],
                    'seats_per_row' => $standData['seats_per_row'],
                ]
            );

            // Criar os lugares para a bancada
            for ($row = 1; $row <= $standData['num_rows']; $row++) {
                for ($seat = 1; $seat <= $standData['seats_per_row']; $seat++) {
                    // Determinar o tipo de lugar (VIP ou Normal)
                    $seatTypeId = ($seat <= $standData['seats_per_row'] / 2) ? 1 : 2; // Metade VIP e metade Normal

                    Seat::updateOrCreate(
                        [
                            'stand_id' => $stand->id,
                            'row_number' => $row,
                            'seat_number' => $seat,
                        ],
                        [
                            'seat_type_id' => $seatTypeId, // Define o tipo de lugar
                            'status' => 'disponível',      // Define o status como disponível por padrão
                        ]
                    );
                }
            }
        }

        // Recarregar o estádio com as bancadas associadas
        $stadium->load('stands');

        // Retornar a view com o layout do estádio
        return view('stadiums.view', compact('stadium'));
    }

public function viewLayout($stadium_id)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // Obter o estádio pelo ID e carregar as bancadas associadas
        $stadium = Stadium::with('stands')->findOrFail($stadium_id);

        // Retornar a view com o layout do estádio
        return view('stadiums.view', compact('stadium'));
    }


}

