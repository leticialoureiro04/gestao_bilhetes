<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class DecryptEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:decrypt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove encryption from user emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            try {
                $user->update([
                    'email' => Crypt::decryptString($user->getRawOriginal('email')), // Remove a criptografia do e-mail
                ]);
                $this->info("Email do usuário ID {$user->id} descriptografado com sucesso.");
            } catch (\Exception $e) {
                $this->error("Erro ao descriptografar o e-mail do usuário ID {$user->id}: {$e->getMessage()}");
            }
        }

        $this->info('Processo concluído!');
        return 0;
    }
}
