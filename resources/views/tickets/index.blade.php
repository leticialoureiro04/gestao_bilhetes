@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('tickets.ticket_list') }}</h3>
        </div>
        <div class="card-body">
            <table id="ticketsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('tickets.id') }}</th>
                        <th>{{ __('tickets.game') }}</th>
                        <th>{{ __('tickets.seat') }}</th>
                        <th>{{ __('tickets.user') }}</th>
                        <th>{{ __('tickets.price') }}</th>
                        <th>{{ __('tickets.status') }}</th>
                        @if(auth()->user()->hasRole('admin'))
                            <th>{{ __('tickets.actions') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->game->stadium->name }} - {{ $ticket->game->date_time }}</td>
                            <td>{{ chr(64 + $ticket->seat->row_number) }}-{{ $ticket->seat->seat_number }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>{{ $ticket->price }}</td>
                            <td>{{( $ticket->status) }}</td>
                            @if(auth()->user()->hasRole('admin'))
                                <td>
                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('tickets.confirm_cancel') }}')">
                                            <i class="fas fa-trash"></i> {{ __('tickets.cancel') }}
                                        </button>
                                    </form>
                                    @if($ticket->status === 'disponível')
                                        <form action="{{ route('tickets.sell', $ticket->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-dollar-sign"></i> {{ ('tickets.sell') }}
                                            </button>
                                        </form>
                                        <form action="{{ route('tickets.reserve', $ticket->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary">
                                                <i class="fas fa-bookmark"></i> {{ __('tickets.reserve') }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">{{ __('tickets.total_tickets') }}: {{ $tickets->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush


