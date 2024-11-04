<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'stadium_plan_id',
        'seat_type_id',
        'status',
    ];

    public function stadiumPlan()
    {
        return $this->belongsTo(StadiumPlan::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }
}
