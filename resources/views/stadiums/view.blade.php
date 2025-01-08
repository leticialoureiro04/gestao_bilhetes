@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">{{ __('stadiums.stadium_view') }} {{ $stadium->name }}</h1>
    <div class="stadium-container">
        <!-- Layout do Estádio -->
        <div class="stadium-layout">
            <!-- Bancada Norte -->
            @php $north = $stadium->stands->where('name', 'Bancada Norte')->first(); @endphp
            <div class="stand north" onclick="showSeats({{ $north->id ?? 'null' }}, 'Bancada Norte')">
                <h3>Bancada Norte</h3>
            </div>

            <!-- Bancada Sul -->
            @php $south = $stadium->stands->where('name', 'Bancada Sul')->first(); @endphp
            <div class="stand south" onclick="showSeats({{ $south->id ?? 'null' }}, 'Bancada Sul')">
                <h3>Bancada Sul</h3>
            </div>

            <!-- Bancada Nascente -->
            @php $east = $stadium->stands->where('name', 'Bancada Nascente')->first(); @endphp
            <div class="stand east" onclick="showSeats({{ $east->id ?? 'null' }}, 'Bancada Nascente')">
                <h3>Bancada Nascente</h3>
            </div>

            <!-- Bancada Poente -->
            @php $west = $stadium->stands->where('name', 'Bancada Poente')->first(); @endphp
            <div class="stand west" onclick="showSeats({{ $west->id ?? 'null' }}, 'Bancada Poente')">
                <h3>Bancada Poente</h3>
            </div>

            <!-- Campo -->
            <div class="field">
                <h3>Field</h3>
            </div>
        </div>

        <!-- Painel Lateral com Lugares e Botão de Compra -->
        <div class="seats-panel" id="seatsPanel">
            <h4 id="panelTitle">{{ __('stadiums.select_stand') }}</h4>
            <div id="seatsView" class="seats-view"></div>
            <form id="purchaseForm" method="POST" action="{{ route('tickets.buy') }}">
                @csrf
                <input type="hidden" name="seat_ids" id="selectedSeats" value="">
                <button id="confirmPurchase" class="btn btn-primary mt-3" disabled>{{ __('stadiums.buy_seats') }}</button>
            </form>
        </div>
    </div>
</div>

<style>
    .stadium-container {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .stadium-layout {
        display: grid;
        grid-template-areas:
            "north north north"
            "west field east"
            "south south south";
        grid-template-rows: 1fr 3fr 1fr;
        grid-template-columns: 1fr 3fr 1fr;
        gap: 20px;
        height: 80vh;
    }

    .stand {
        background-color: #343a40;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        font-size: 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        padding: 20px;
    }

    .north { grid-area: north; background-color: blue; }
    .south { grid-area: south; background-color: red; }
    .east  { grid-area: east; background-color: orange; }
    .west  { grid-area: west; background-color: #daa520; }

    .field {
        grid-area: field;
        background-color: #28a745;
        color: white;
        font-size: 2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 8px;
    }

    .seats-panel {
        width: 35%;
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 20px;
        background-color: #f8f9fa;
        overflow-y: auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .seats-view {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .seat-row {
        display: flex;
        gap: 5px;
    }

    .seat {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 5px;
        font-size: 14px;
        color: white;
        cursor: pointer;
    }

    .available { background-color: green; }
    .occupied { background-color: red; cursor: not-allowed; }
    .vip { background-color: gold; color: black; }
    .btn-selected { background-color: #007bff; color: white; }
</style>

<script>
    let selectedSeats = [];

    async function showSeats(standId, standName) {
    const seatsView = document.getElementById('seatsView');
    const panelTitle = document.getElementById('panelTitle');
    const confirmButton = document.getElementById('confirmPurchase');

    panelTitle.innerText = `{{ __('stadiums.seats') }} - ${standName}`;
    seatsView.innerHTML = '<p>{{ __('stadiums.loading_seats') }}</p>';
    selectedSeats = [];
    confirmButton.disabled = true;

    try {
        const response = await fetch(`/stands/${standId}/seats`);
        const seats = await response.json();

        console.log("Lugares carregados:", seats); // Verificar os dados

        seatsView.innerHTML = '';
        const rows = {};

        seats.forEach(seat => {
            if (!rows[seat.row_number]) rows[seat.row_number] = [];
            rows[seat.row_number].push(seat);
        });

        Object.keys(rows).forEach(rowNumber => {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'seat-row';

            rows[rowNumber].forEach(seat => {
                if (!seat.id) {
                    console.error("ID do lugar está undefined:", seat);
                    return;
                }

                const seatDiv = document.createElement('div');
                seatDiv.className = 'seat ' + getSeatClass(seat);
                seatDiv.innerText = `${String.fromCharCode(64 + seat.row_number)}-${seat.seat_number}`;

                seatDiv.dataset.seatId = seat.id;
                seatDiv.dataset.status = seat.status;

                seatDiv.onclick = function () {
                    toggleSeatSelection(seatDiv.dataset.seatId, seatDiv, seatDiv.dataset.status);
                };

                rowDiv.appendChild(seatDiv);
            });
            seatsView.appendChild(rowDiv);
        });
    } catch (error) {
        console.error('Erro ao carregar lugares:', error);
        seatsView.innerHTML = '<p>Erro ao carregar lugares.</p>';
    }
}


    function toggleSeatSelection(seatId, seatDiv, status) {
        if (status === 'vendido') return;

        if (selectedSeats.includes(seatId)) {
            selectedSeats = selectedSeats.filter(id => id !== seatId);
            seatDiv.classList.remove('btn-selected');
        } else {
            selectedSeats.push(seatId);
            seatDiv.classList.add('btn-selected');
        }

        console.log("Assentos selecionados:", selectedSeats);
        document.getElementById('confirmPurchase').disabled = selectedSeats.length === 0;
        document.getElementById('selectedSeats').value = JSON.stringify(selectedSeats);
    }

    function getSeatClass(seat) {
        if (seat.status === 'vendido') return 'occupied';
        if (seat.seat_type_id == 1) return 'vip';
        return 'available';
    }
</script>
@endsection







