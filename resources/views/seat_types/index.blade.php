@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('seat_types.list_of_seat_types') }}</h3>
            <a href="{{ route('seat_types.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> {{ __('seat_types.add_seat_type') }}
            </a>
        </div>
        <div class="card-body">
            <table id="seatTypesTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('seat_types.seat_type_name') }}</th>
                        <th>{{ __('seat_types.description') }}</th>
                        <th>{{ __('seat_types.price') }}</th>
                        <th>{{ __('seat_types.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seatTypes as $seatType)
                        <tr>
                            <td>{{ $seatType->id }}</td>
                            <td>{{ $seatType->name }}</td>
                            <td>{{ $seatType->description }}</td>
                            <td>{{ $seatType->price }}</td>
                            <td>
                                <a href="{{ route('seat_types.edit', $seatType->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('seat_types.edit_seat_type') }}
                                </a>
                                <form action="{{ route('seat_types.destroy', $seatType->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('seat_types.confirm_delete') }}')">
                                        <i class="fas fa-trash"></i> {{ __('seat_types.delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <p class="text-muted">{{ __('seat_types.total_seat_types') }}: {{ $seatTypes->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#seatTypesTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush



