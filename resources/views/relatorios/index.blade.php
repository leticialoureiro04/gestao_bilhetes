@extends('layouts.app')

@section('title', __('Relatórios'))

@section('content')
<div class="container-fluid">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Relatórios de Exportação') }}
    </h2>

    <div class="row mt-4">
        <div class="col-lg-12">
            <form action="{{ route('export.map') }}" method="GET" class="form-inline">
                <div class="form-group mx-2">
                    <label for="stadium" class="mr-2">Estádio</label>
                    <select name="stadium" id="stadium" class="form-control">
                        <option value="">Todos</option>
                        @foreach($stadiums as $stadium)
                            <option value="{{ $stadium->id }}">{{ $stadium->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mx-2">
                    <label for="bancada" class="mr-2">Bancada</label>
                    <select name="bancada" id="bancada" class="form-control">
                        <option value="">Todas</option>
                        @foreach($bancadas as $bancada)
                            <option value="{{ $bancada->id }}">{{ $bancada->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" name="export_type" value="pdf" class="btn btn-danger mx-2">Exportar PDF</button>
                <button type="submit" name="export_type" value="excel" class="btn btn-success mx-2">Exportar Excel</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('stadium').addEventListener('change', function () {
            const stadiumId = this.value;
            const bancadaSelect = document.getElementById('bancada');

            // Limpar opções existentes
            bancadaSelect.innerHTML = '<option value="">Todas</option>';

            if (stadiumId) {
                fetch(`/bancadas/${stadiumId}`)
                    .then(response => response.json())
                    .then(data => {
                        Object.entries(data).forEach(([id, name]) => {
                            const option = document.createElement('option');
                            option.value = id;
                            option.textContent = name;
                            bancadaSelect.appendChild(option);
                        });
                    });
            }
        });
    </script>
</div>
@endsection
