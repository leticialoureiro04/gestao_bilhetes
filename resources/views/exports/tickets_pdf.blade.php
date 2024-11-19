<!DOCTYPE html>
<html>
<head>
    <title>Bilhetes Exportados</title>
</head>
<body>
    <h1>Lista de Bilhetes</h1>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Estádio</th>
                <th>Bancada</th>
                <th>Assento</th>
                <th>Preço</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->stadium }}</td>
                    <td>{{ $ticket->stand }}</td>
                    <td>{{ $ticket->seat }}</td>
                    <td>{{ $ticket->price }}</td>
                    <td>{{ $ticket->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
