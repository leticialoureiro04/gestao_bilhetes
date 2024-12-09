<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Stadium;
use App\Models\Stand;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TicketsExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class ExportController extends Controller
{
    // Função para exibir a nova página de relatórios
    public function show()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // Obter estádios e bancadas para os filtros
        $stadiums = Stadium::all();
        $bancadas = Stand::distinct('name')->get();

        return view('relatorios.index', compact('stadiums', 'bancadas'));
    }

    public function getBancadas($stadiumId)
    {
        $bancadas = Stand::where('stadium_id', $stadiumId)->pluck('name', 'id');
        return response()->json($bancadas);
    }

    // Função para processar a exportação
    public function exportMap(Request $request)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

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

    // Exportar para PDF e enviar por email
    private function exportPDF($tickets)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        // Geração do PDF
        $pdf = Pdf::loadView('exports.tickets_pdf', compact('tickets'));

        // Caminho para salvar temporariamente o PDF
        $filePath = storage_path('app/public/bilhetes.pdf');
        $pdf->save($filePath);

        // Configurar destinatário e enviar o email
        $user = (object) [
            'name' => 'Administrador',
            'email' => 'diogoazevedo@ipvc.pt', // Substituir pelo email do destinatário
        ];
        Mail::to($user->email)->send(new TestEmail($user, $filePath));

        // Retornar o PDF para download após o envio do email
        return response()->download($filePath)->deleteFileAfterSend();
    }

    // Exportar para Excel e enviar por email
    private function exportExcel($tickets)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $stadiumId = request()->input('stadium');
        $bancadaId = request()->input('bancada');

        // Gere o arquivo Excel
        $filePath = storage_path('app/private/public/bilhetes.xlsx');
        Excel::store(new TicketsExport($stadiumId, $bancadaId), 'public/bilhetes.xlsx');

        // Envie o arquivo por e-mail
        $this->sendEmailWithAttachment($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
    private function sendEmailWithAttachment($filePath, $fileName = 'relatorio.xlsx')
    {
        // Simulando um usuário para enviar o email
        $user = (object) [
            'name' => 'Administrador',
            'email' => 'diogoazevedo@ipvc.pt', // Substituir pelo email do destinatário
        ];

        Mail::send('emails.test', ['user' => $user], function ($message) use ($filePath, $fileName, $user) {
            $message->to($user->email)
                ->subject('Relatório de Bilhetes Exportado')
                ->attach($filePath, [
                    'as' => $fileName,
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
        });
    }


}



