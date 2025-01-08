<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    // Campos permitidos para inserção em massa
    protected $fillable = [
        'stadium_id',
        'stadium_plan_id',
        'seat_type_id',
        'stand_id',
        'row_number',
        'seat_number',
        'status',
    ];

    /**
     * Relação com o modelo StadiumPlan
     */
    public function stadiumPlan()
    {
        return $this->belongsTo(StadiumPlan::class);
    }

    /**
     * Relação com o modelo SeatType
     */
    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }

    /**
     * Relação com o modelo Stand
     */
    public function stand()
    {
        return $this->belongsTo(Stand::class);
    }

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    /**
     * Escopo para filtrar assentos por estádio
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $stadiumId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStadium($query, $stadiumId)
    {
        return $query->whereHas('stand', function ($q) use ($stadiumId) {
            $q->where('stadium_id', $stadiumId);
        });
    }

    /**
     * Validações de atributos no modelo
     */
    public static $rules = [
        'seat_number' => 'required|integer|unique:seats,seat_number,NULL,id,stadium_plan_id', // Único por estádio
        'seat_type_id' => 'required|exists:seat_types,id',
        'stand_id' => 'required|exists:stands,id',
        'row_number' => 'required|integer|min:1',
        'status' => 'required|in:disponível,ocupado',
    ];

    /**
     * Método para calcular o número sequencial do assento
     *
     * @param int $stadiumPlanId
     * @return int
     */
    public static function getNextSeatNumber($stadiumPlanId)
    {
        // Obtém o último número de assento no mesmo plano do estádio
        $lastSeat = self::where('stadium_plan_id', $stadiumPlanId)->max('seat_number');
        return $lastSeat ? $lastSeat + 1 : 1;
    }

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_seat')->withPivot('status')->withTimestamps();
    }



}

