<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class InvoiceController extends Controller
{
    /**
     * Mostra a lista de faturas.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $user = Auth::user();

        // Verifica se o utilizador tem o papel de admin
        if ($user->roles->contains('name', 'admin')) {
            // Admin vê todas as faturas com o utilizador relacionado
            $invoices = Invoice::with(['user'])->get();
        } else {
            // Clientes veem apenas as suas próprias faturas
            $invoices = Invoice::where('user_id', $user->id)
                               ->with(['user']) // Inclui o relacionamento
                               ->get();
        }

        return view('invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);

        $invoice = Invoice::with('user')->findOrFail($id); // Carrega o cliente associado

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Paga a fatura no sistema.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay($id)
    {
        $invoice = Invoice::findOrFail($id);

        // Verificar se a fatura já está paga
        if ($invoice->status === 'paga') {
            return redirect()->route('invoices.index')->with('info', 'Esta fatura já foi paga.');
        }

        // Atualizar o status da fatura
        $invoice->status = 'paga';
        $invoice->saldo = 0; // Saldo zerado após pagamento
        $invoice->save();

        return redirect()->route('invoices.index')->with('success', 'Fatura paga com sucesso!');
    }
}
