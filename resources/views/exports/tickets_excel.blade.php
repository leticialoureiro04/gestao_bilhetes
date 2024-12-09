<table>
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
                <td>{{ $ticket->stadium }}</td>
                <td>{{ $ticket->stand }}</td>
                <td>{{ $ticket->seat }}</td>
                <td>{{ $ticket->price }}</td>
                <td>{{ $ticket->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
