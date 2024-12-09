<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    // Define os campos que podem ser preenchidos via mass assignment
    protected $fillable = [
        'ticket_id',
        'total_amount',
        'saldo',
        'issue_date',
        'expiration',
        'status',
        'user_id',
        'title',
        'invoice_id', // Novo campo
    ];

    // Relacionamento com Tickets
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Relacionamento com Users através do Ticket
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}

