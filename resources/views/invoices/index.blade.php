@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('invoices.list_of_invoices') }}</h3>
        </div>
        <div class="card-body">
            <table id="invoicesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('invoices.id') }}</th>
                        <th>{{ __('invoices.client') }}</th>
                        <th>{{ __('invoices.total_amount') }}</th>
                        <th>{{ __('invoices.balance') }}</th>
                        <th>{{ __('invoices.status') }}</th>
                        <th>{{ __('invoices.issue_date') }}</th>
                        <th>{{ __('invoices.expiration_date') }}</th>
                        <th>{{ __('invoices.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td>{{ $invoice->user->name ?? __('invoices.information_unavailable') }}</td>
                            <td>{{ number_format($invoice->total_amount, 2, ',', '.') }} €</td>
                            <td>{{ number_format($invoice->saldo, 2, ',', '.') }} €</td>
                            <td>
                                @if($invoice->status === 'paga')
                                    <span class="badge bg-success">{{ __('invoices.paid') }}</span>
                                @elseif($invoice->status === 'pendente')
                                    <span class="badge bg-warning">{{ __('invoices.pending') }}</span>
                                @elseif($invoice->status === 'cancelada')
                                    <span class="badge bg-danger">{{ __('invoices.canceled') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('invoices.unknown') }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y H:i:s') }}</td>
                            <td>{{ \Carbon\Carbon::parse($invoice->expiration)->format('d/m/Y H:i:s') }}</td>
                            <td>
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> {{ __('invoices.view') }}
                                </a>

                                @if($invoice->status === 'pendente')
                                    <form action="{{ route('invoices.pay', $invoice->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('{{ __('invoices.confirm_payment') }}')">
                                            <i class="fas fa-credit-card"></i> {{ __('invoices.pay') }}
                                        </button>
                                    </form>
                                @endif
                            </td>
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
        $('#invoicesTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush




