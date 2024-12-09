@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('seats.list_of_seats') }}</h3>
        </div>
        <div class="card-body">
            <table id="seatsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('seats.stadium') }}</th>
                        <th>{{ __('seats.stand') }}</th>
                        <th>{{ __('seats.row_number') }}</th>
                        <th>{{ __('seats.seat_number') }}</th>
                        <th>{{ __('seats.seat_type') }}</th>
                        <th>{{ __('seats.status') }}</th>
                        <th>{{ __('seats.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seats as $seat)
                        <tr>
                            <td>{{ $seat->stand->stadium->name ?? __('seats.no_information') }}</td>
                            <td>{{ $seat->stand->name ?? __('seats.no_information') }}</td>
                            <td>{{ chr(64 + $seat->row_number) }}</td>
                            <td>{{ $seat->seat_number ?? __('seats.no_information') }}</td>
                            <td>{{ $seat->seatType->name ?? __('seats.no_information') }}</td>
                            <td>{{ $seat->status ?? __('seats.no_information') }}</td>
                            <td>
                                <a href="{{ route('seats.edit', $seat->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('seats.edit_seat') }}
                                </a>
                                <form action="{{ route('seats.destroy', $seat->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('seats.confirm_delete') }}')">
                                        <i class="fas fa-trash"></i> {{ __('seats.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">{{ __('seats.total_seats') }}: {{ $seats->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#seatsTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush



