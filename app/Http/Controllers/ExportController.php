<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Stadium;
use App\Models\Stand;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TicketsExport;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    // Função para exibir a nova página de relatórios
    public function show()
    {
        // Obter estádios e bancadas para os filtros
        $stadiums = Stadium::all();
        $bancadas = Stand::all();

        return view('relatorios.index', compact('stadiums', 'bancadas'));
    }

    // Função para processar a exportação
    public function exportMap(Request $request)
{
    // Captura os filtros do formulário
    $stadiumId = $request->input('stadium');
    $bancadaId = $request->input('bancada');
    $exportType = $request->input('export_type');

    // Query inicial para filtrar os bilhetes
    $query = Ticket::query()
        ->join('seats', 'tickets.seat_id', '=', 'seats.id')
        ->join('stands', 'seats.stand_id', '=', 'stands.id')
        ->join('stadiums', 'stands.stadium_id', '=', 'stadiums.id')
        ->select(
            'tickets.created_at',
            'stadiums.name as stadium',
            'stands.name as stand',
            'seats.row_number',
            'seats.seat_number',
            'tickets.price'
        );

    // Aplicar filtro por Estádio, se selecionado
    if (!empty($stadiumId)) {
        $query->where('stadiums.id', $stadiumId);
    }

    // Aplicar filtro por Bancada, se selecionado
    if (!empty($bancadaId)) {
        $query->where('stands.id', $bancadaId);
    }

    // Obter os resultados filtrados
    $tickets = $query->get();

    // Processar de acordo com o tipo de exportação
    if ($exportType === 'pdf') {
        return $this->exportPDF($tickets);
    } elseif ($exportType === 'excel') {
        return $this->exportExcel($tickets);
    }

    // Retornar erro se o tipo de exportação não for válido
    return redirect()->back()->with('error', 'Tipo de exportação inválido.');
}



    // Exportar para PDF
    private function exportPDF($tickets)
    {
        $pdf = Pdf::loadView('exports.tickets_pdf', compact('tickets'));
        return $pdf->download('bilhetes.pdf');
    }

    // Exportar para Excel
    private function exportExcel($tickets)
{
    $stadiumId = request()->input('stadium');
    $bancadaId = request()->input('bancada');
    return Excel::download(new TicketsExport($stadiumId, $bancadaId), 'bilhetes.xlsx');
}

}


