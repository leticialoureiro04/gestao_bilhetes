<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $filePath;

    public function __construct($user, $filePath)
    {
        $this->user = $user;
        $this->filePath = $filePath;
    }

    public function build()
    {
        return $this
            ->subject('Relatório de Bilhetes Vendidos')
            ->view('emails.test')
            ->attach($this->filePath, [
                'as' => 'relatorio.' . pathinfo($this->filePath, PATHINFO_EXTENSION),
            ]);
    }
}

