<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['stadium_id', 'date_time'];

    // Relacionamento com o modelo Stadium
    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    // Relacionamento com o modelo Team (muitos para muitos através de game_teams)
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'game_teams')
                    ->withPivot('role'); // Inclui a coluna 'role' (casa ou visitante) do pivot
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'game_seat')->withPivot('status')->withTimestamps();
    }

}
