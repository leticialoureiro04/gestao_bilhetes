<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    protected $table = 'stadiums';


    protected $fillable = ['name', 'location', 'capacity', 'num_stands'];
    public function plans()
    {
        return $this->hasMany(StadiumPlan::class);
    }

    public function game()
    {
        return $this->hasOne(Game::class);
    }

    public function stands()
{
    return $this->hasMany(Stand::class);
}
}
