<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    // Definir os campos que podem ser preenchidos em massa
    protected $fillable = ['game_id', 'seat_id', 'user_id', 'price', 'status'];

    // Relação com o modelo Game
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // Relação com o modelo Seat para obter informações do lugar associado ao bilhete
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    // Relação com o modelo User para obter informações do utilizador que comprou o bilhete
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação com o modelo Invoice para obter a fatura associada ao bilhete
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}

