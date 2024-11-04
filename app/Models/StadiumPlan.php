<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StadiumPlan extends Model
{
    use HasFactory;

    protected $fillable = ['stadium_id', 'name', 'description'];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }
}

