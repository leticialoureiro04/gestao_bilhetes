@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Lugares para a Planta: {{ $stadiumPlan->name }}</h3>
    <canvas id="stadiumCanvas" width="800" height="600" style="border:1px solid #000000;"></canvas>
    <button id="buyTickets" class="btn btn-primary mt-3" disabled>Comprar Bilhetes</button>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('stadiumCanvas');
        const ctx = canvas.getContext('2d');
        let selectedSeats = []; // Array para armazenar lugares selecionados

        // Configuração inicial do fundo do estádio
        ctx.fillStyle = '#a8e6cf'; // Verde claro para o fundo do estádio
        ctx.fillRect(50, 50, 700, 500);


        const seats = @json($seats).map(seat => {
            return {
                ...seat,
                x_position: seat.x_position + 100,
                y_position: seat.y_position + 350
            };
        });

        // Função para desenhar os lugares como círculos
        function drawSeats() {
            seats.forEach(seat => {
                ctx.fillStyle = seat.status === 'vendido' ? 'red' : 'green';
                ctx.beginPath();
                ctx.arc(seat.x_position, seat.y_position, 10, 0, 2 * Math.PI);
                ctx.fill();
                ctx.closePath();
            });
        }

        // Desenhar os lugares inicialmente
        drawSeats();

        // Detectar clique e alternar a cor do lugar
        canvas.addEventListener('click', function(event) {
            const rect = canvas.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            seats.forEach(seat => {
                const distance = Math.sqrt((x - seat.x_position) ** 2 + (y - seat.y_position) ** 2);

                // Verificar se o clique está dentro do raio do círculo do lugar
                if (distance <= 10 && seat.status !== 'vendido') {
                    // Alternar a seleção do lugar
                    seat.color = seat.color === 'green' ? 'blue' : 'green';

                    if (seat.color === 'blue') {
                        if (!selectedSeats.includes(seat.id)) {
                            selectedSeats.push(seat.id);
                        }
                    } else {
                        selectedSeats = selectedSeats.filter(id => id !== seat.id);
                    }

                    // Atualizar o canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.fillStyle = '#a8e6cf';
                    ctx.fillRect(50, 50, 700, 500); 
                    drawSeats();
                }
            });

            // Habilitar o botão de compra se houver lugares selecionados
            document.getElementById('buyTickets').disabled = selectedSeats.length === 0;
        });

        // Função para enviar os lugares selecionados para o servidor
        document.getElementById('buyTickets').addEventListener('click', function() {
            if (selectedSeats.length === 0) {
                alert("Por favor, selecione pelo menos um lugar antes de comprar.");
                return;
            }

            fetch('/buy-tickets', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ seats: selectedSeats })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Compra realizada com sucesso!');
                    location.reload();
                } else {
                    alert(data.message || 'Erro ao realizar a compra. Por favor, tente novamente.');
                }
            })
            .catch(error => {
                console.error('Erro na compra:', error);
                alert('Ocorreu um erro ao tentar comprar os bilhetes.');
            });
        });

    });
</script>
@endpush









