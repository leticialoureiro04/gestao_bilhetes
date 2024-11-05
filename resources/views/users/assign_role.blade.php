@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">Atribuir Papel a {{ $user->name }}</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('users.assign_role.store', $user->id) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="role">Selecione o Papel</label>
                    <select class="form-control" name="role" id="role">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <button type="submit" class="btn btn-info">Atribuir Papel</button>
            </div>
        </form>
    </div>
</div>
@endsection




