<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;


class TicketsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $stadiumId;
    protected $bancadaId;

    public function __construct($stadiumId = null, $bancadaId = null)
    {
        $this->stadiumId = $stadiumId;
        $this->bancadaId = $bancadaId;
    }

    public function query()
    {
        $query = Ticket::query()
            ->join('seats', 'tickets.seat_id', '=', 'seats.id')
            ->join('stands', 'seats.stand_id', '=', 'stands.id')
            ->join('stadiums', 'stands.stadium_id', '=', 'stadiums.id')
            ->select(
                'tickets.created_at',
                'stadiums.name as stadium',
                'stands.name as stand',
                'seats.row_number',
                'seats.seat_number',
                'tickets.price'
            );

        if (!empty($this->stadiumId)) {
            $query->where('stadiums.id', $this->stadiumId);
        }

        if (!empty($this->bancadaId)) {
            $query->where('stands.id', $this->bancadaId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Data',
            'Estádio',
            'Bancada',
            'Fila',
            'Assento',
            'Preço',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->created_at->format('d/m/Y'),
            $ticket->stadium,
            $ticket->stand,
            chr(64 + $ticket->row_number),
            $ticket->seat_number,
            number_format($ticket->price, 2, ',', '.'),
        ];
    }
}



