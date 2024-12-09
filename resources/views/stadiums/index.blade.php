@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __('stadiums.list_of_stadiums') }}</h3>
            <div class="card-tools">
                <a href="{{ route('stadiums.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> {{ __('stadiums.add_stadium') }}
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="stadiumsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('stadiums.name') }}</th>
                        <th>{{ __('stadiums.location') }}</th>
                        <th>{{ __('stadiums.capacity') }}</th>
                        <th>{{ __('stadiums.num_stands') }}</th>
                        <th>{{ __('stadiums.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stadiums as $stadium)
                        <tr>
                            <td>{{ $stadium->name }}</td>
                            <td>{{ $stadium->location }}</td>
                            <td>{{ $stadium->capacity }}</td>
                            <td>{{ $stadium->num_stands }}</td>
                            <td>
                                <a href="{{ route('stands.show', $stadium->id) }}" class="btn btn-info btn-sm"> <i class="fas fa-eye"></i>
                                    {{ __('stadiums.view_stands') }}
                                </a>
                                <a href="{{ route('stadiums.viewLayout', $stadium->id) }}" class="btn btn-primary btn-sm"> <i class="fas fa-eye"></i>
                                    {{ __('stadiums.view_stadium') }}
                                </a>
                                <a href="{{ route('stadiums.edit', $stadium->id) }}" class="btn btn-warning btn-sm"> <i class="fas fa-edit"></i>
                                    {{ __('stadiums.edit_stadium') }}
                                </a>
                                <form action="{{ route('stadiums.destroy', $stadium->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('stadiums.confirm_delete') }}')"> <i class="fas fa-trash"></i>
                                        {{ __('stadiums.delete_stadium') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <p class="mb-0">{{ __('stadiums.total_stadiums') }}: {{ $stadiums->count() }}</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stadiumsTable').DataTable({
            responsive: true,
        });
    });
</script>
@endpush



