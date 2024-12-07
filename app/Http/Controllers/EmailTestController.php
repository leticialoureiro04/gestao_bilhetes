<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    public function sendTestEmail()
    {
        $user = [
            'name' => 'Letícia Cruz Loureiro',
            'email' => 'leticiacruzloureiro2004@gmail.com'
        ]; // Informações do usuário para personalizar o e-mail

        $filePath = public_path('files/relatorio-teste.pdf'); // Caminho do arquivo a ser anexado

        // Verifique se o arquivo existe antes de enviar o e-mail
        if (!file_exists($filePath)) {
            return 'Arquivo não encontrado: ' . $filePath;
        }

        Mail::to($user['email'])->send(new \App\Mail\TestEmail($user, $filePath));

        return 'E-mail enviado com sucesso!';
    }
}


