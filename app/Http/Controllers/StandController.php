<?php

namespace App\Http\Controllers;

use App\Models\Stadium;
use App\Models\Stand;
use App\Models\Seat;
use App\Models\SeatType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class StandController extends Controller
{
    // Método para mostrar a página de configuração das bancadas
    public function configure($stadium_id)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // Obtém o estádio pelo ID
        $stadium = Stadium::findOrFail($stadium_id);
        $seatTypes = SeatType::all();

        // Retorna a view de configuração das bancadas com os dados do estádio
        return view('stands.configure', compact('stadium', 'seatTypes'));
    }

    // Método para guardar as bancadas configuradas na base de dados
    public function store(Request $request)
    {


        // Validação dos dados recebidos
        $request->validate([
            'stadium_id' => 'required|exists:stadiums,id',
            'stands.*.name' => 'required|string|max:255',
            'stands.*.num_rows' => 'required|integer|min:1',
            'stands.*.seats_per_row' => 'required|integer|min:1',
            'stands.*.seat_types.1' => 'required|integer|min:0', // Lugares VIP
            'stands.*.seat_types.2' => 'required|integer|min:0', // Lugares Normais
        ]);

        // Obtém o ID do estádio
        $stadium_id = $request->stadium_id;

        // Loop através de cada bancada para guardar na base de dados
        foreach ($request->stands as $standData) {
            // Criar a bancada
            $stand = Stand::create([
                'stadium_id' => $stadium_id,
                'name' => $standData['name'],
                'num_rows' => $standData['num_rows'],
                'seats_per_row' => $standData['seats_per_row'],
            ]);


            /*// Obter o plano do estádio (assumindo que está associado ao estádio)
            $stadiumPlanId = $stand->stadium->stadium_plan_id ?? null;
            if (!$stadiumPlanId) {
                return redirect()->back()->withErrors(['error' => 'Plano do estádio não encontrado para a bancada.']);
            }*/
            // Geração automática de lugares
            $vipSeats = $standData['seat_types'][1] ?? 0; // Lugares VIP
            $normalSeats = $standData['seat_types'][2] ?? 0; // Lugares Normais


            for ($row = 1; $row <= $standData['num_rows']; $row++) {
                for ($seatNumber = 1; $seatNumber <= $standData['seats_per_row']; $seatNumber++) {
                    $seatType = $vipSeats > 0 ? 1 : 2; // 1 = VIP, 2 = Normal
                    $vipSeats = $vipSeats > 0 ? $vipSeats - 1 : $vipSeats;

                    // Criar o assento
                    Seat::create([
                        'stand_id' => $stand->id,
                        /*'stadium_plan_id' => $stadiumPlanId,*/
                        'seat_type_id' => $seatType,
                        'row_number' => $row,
                        'seat_number' => $seatNumber,
                        'status' => 'disponível',
                    ]);
                }
            }
        }

        // Redireciona para a página de listagem de estádios com mensagem de sucesso
        return redirect()->route('stadiums.index')->with('success', 'Bancadas e lugares configurados com sucesso!');
    }

    // Método para exibir as bancadas de um estádio
    public function show($stadium_id)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadium = Stadium::with('stands')->findOrFail($stadium_id);
        return view('stands.show', compact('stadium'));
    }

    // Método para armazenar a configuração de lugares para uma bancada
    public function storeSeats(Request $request, Stand $stand)
    {
        $request->validate([
            'seats' => 'required|array',
            'seats.*' => 'required|integer|min:0', // Valida cada tipo de lugar como um inteiro
        ]);

        // Armazena as configurações de lugares por tipo para a bancada
        foreach ($request->seats as $seatTypeId => $quantity) {
            // Cria registos de lugares na base de dados ou atualize conforme necessário
            $stand->seats()->updateOrCreate(
                ['seat_type_id' => $seatTypeId],
                ['quantity' => $quantity]
            );
        }

        return redirect()->route('stands.show', $stand->stadium_id)->with('success', 'Configuração de lugares guardada com sucesso!');
    }

    public function showSeats($stand_id)
{
    $locale = Session::get('locale', config('app.locale'));
     App::setLocale($locale);

    $stand = Stand::with('seats')->findOrFail($stand_id);

    return view('stands.show', compact('stand'));
}

public function getSeats($standId)
{
    $seats = Seat::where('stand_id', $standId)
        ->get(['id', 'row_number', 'seat_number', 'status', 'seat_type_id']);

    // Corrige os dados para garantir que o status tenha valores válidos
    foreach ($seats as $seat) {
        if (!$seat->status) {
            $seat->status = 'disponível';
        }
    }

    return response()->json($seats);
}

    public function view($standId)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // Procurar a bancada pelo ID e carregar os assentos associados
        $stand = Stand::with('seats')->findOrFail($standId);

        // Retornar a view com os dados da bancada e assentos
        return view('stands.view', compact('stand'));
    }

}
