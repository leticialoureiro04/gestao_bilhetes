<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
    use HasFactory;

    protected $fillable = ['stadium_id', 'name', 'num_rows', 'seats_per_row'];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
