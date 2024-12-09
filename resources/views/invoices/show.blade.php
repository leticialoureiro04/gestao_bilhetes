@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('invoices.invoice_details') }} #{{ $invoice->id }}</h3>
        </div>
        <div class="card-body">
            <p><strong>{{ __('invoices.client') }}:</strong> {{ $invoice->user->name ?? __('invoices.information_unavailable') }}</p>
            <p><strong>{{ __('invoices.total_amount') }}:</strong> {{ number_format($invoice->total_amount, 2, ',', '.') }} €</p>
            <p><strong>{{ __('invoices.balance') }}:</strong> {{ number_format($invoice->saldo, 2, ',', '.') }} €</p>
            <p><strong>{{ __('invoices.status') }}:</strong>
                @if($invoice->status === 'paga')
                    <span class="badge bg-success">{{ __('invoices.paid') }}</span>
                @elseif($invoice->status === 'pendente')
                    <span class="badge bg-warning">{{ __('invoices.pending') }}</span>
                @elseif($invoice->status === 'cancelada')
                    <span class="badge bg-danger">{{ __('invoices.canceled') }}</span>
                @endif
            </p>
            <p><strong>{{ __('invoices.issue_date') }}:</strong> {{ $invoice->issue_date }}</p>
            <p><strong>{{ __('invoices.expiration_date') }}:</strong> {{ $invoice->expiration }}</p>

            <hr>

            <h5>{{ __('invoices.ticket_information') }}</h5>
            @php
                $ticketIds = explode(',', $invoice->ticket_id); // If ticket_id is a comma-separated string
                $tickets = \App\Models\Ticket::whereIn('id', $ticketIds)->get(); // Retrieve related tickets
            @endphp

            @if($tickets->isNotEmpty())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('invoices.ticket_id') }}</th>
                            <th>{{ __('invoices.seat') }}</th>
                            <th>{{ __('invoices.price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->id }}</td>
                                <td>
                                    @php
                                        // Convert the row number to a letter (A, B, C, etc.)
                                        $rowLetter = chr(64 + $ticket->seat->row_number); // Example: 1 -> A
                                        $seatNumber = $ticket->seat->seat_number ?? __('invoices.information_unavailable');
                                    @endphp
                                    {{ $rowLetter }}-{{ $seatNumber }}
                                </td>
                                <td>{{ number_format($ticket->price, 2, ',', '.') }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>{{ __('invoices.no_tickets') }}</p>
            @endif

            <a href="{{ route('invoices.index') }}" class="btn btn-secondary mt-3">{{ __('invoices.back') }}</a>
        </div>
    </div>
</div>
@endsection


