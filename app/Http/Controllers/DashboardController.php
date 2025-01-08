<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    public function index()
    {

        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // Consulta para obter o número de bilhetes vendidos por tipo de assento
        $ticketsByType = DB::table('tickets')
            ->join('seats', 'tickets.seat_id', '=', 'seats.id') // Relaciona tickets com seats
            ->join('seat_types', 'seats.seat_type_id', '=', 'seat_types.id') // Relaciona seats com seat_types
            ->select('seat_types.name as type', DB::raw('COUNT(tickets.id) as total'))
            ->groupBy('seat_types.name') // Corrige o grupo pela coluna correta
            ->get();

        // Consulta para obter as vendas por mês
        $sales = DB::table('tickets')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(id) as total'))
            ->groupBy(DB::raw('MONTH(created_at)')) // Agrupa pelo número do mês
            ->orderBy('month', 'asc') // Ordena para garantir a sequência correta
            ->get();

        return view('dashboard', compact('ticketsByType', 'sales'));
    }
}

