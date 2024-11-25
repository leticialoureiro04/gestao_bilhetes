<!DOCTYPE html>
<html>
<head>
    <title>Exported Tickets</title>
</head>
<body>
    <h1>Ticket List</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Stadium</th>
                <th>Stand</th>
                <th>Seat</th>
                <th>Price</th>
                <th>Creation Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->stadium }}</td> <!-- Nome do estádio -->
                    <td>{{ $ticket->stand }}</td> <!-- Nome da bancada -->
                    <td>{{ chr(64 + $ticket->row_number) . $ticket->seat_number }}</td> <!-- Assento (Fila + Número) -->
                    <td>{{ number_format($ticket->price, 2, ',', '.') }}</td> <!-- Preço formatado -->
                    <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d/m/Y H:i:s') }}</td> <!-- Data formatada -->
                </tr>
            @endforeach
        </tbody>

    </table>
</body>
</html>
