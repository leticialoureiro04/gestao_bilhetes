<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class EmailTestController extends Controller
{
    public function sendTestEmail()
    {
        $details = [
            'title' => 'Teste de E-mail',
            'body' => 'Este é um teste de envio de e-mail com o SendGrid em Laravel.'
        ];

        Mail::to('leticiacruzloureiro2004@gmail.com')->send(new \App\Mail\TestEmail($details));

        return 'E-mail enviado com sucesso!';
    }
}

