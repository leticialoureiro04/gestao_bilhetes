@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Meus Bilhetes</h3>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Planta</th>
                        <th>Tipo de Lugar</th>
                        <th>Status</th>
                        <th>Data de Compra</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->seat->stadiumPlan->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->seat->seatType->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->status }}</td>
                            <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stadiumPlansTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush


