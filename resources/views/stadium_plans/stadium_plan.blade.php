@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Lugares para a Planta: {{ $stadiumPlan->name }}</h3>
    <canvas id="stadiumCanvas" width="800" height="600" style="border:1px solid #000000;"></canvas>
    <button id="buyTickets" class="btn btn-primary mt-3">Comprar Bilhetes</button>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('stadiumCanvas');
        const ctx = canvas.getContext('2d');
        let selectedSeats = []; // Array para armazenar lugares selecionados

        // Configuração inicial do campo (campo de jogo)
        ctx.fillStyle = 'green';
        ctx.fillRect(200, 150, 400, 300);

        // Assumindo que os lugares foram passados do controlador como uma variável JavaScript
        const seats = @json($seats); // Isso recupera os lugares da planta atual

        // Função para desenhar os lugares como círculos
        function drawSeats() {
            seats.forEach(seat => {
                ctx.fillStyle = seat.status === 'vendido' ? 'gray' : seat.color || 'blue';
                ctx.beginPath();
                ctx.arc(seat.x_position, seat.y_position, 10, 0, 2 * Math.PI); // Desenhar círculos para os lugares
                ctx.fill();
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
                    seat.color = seat.color === 'blue' ? 'red' : 'blue';

                    if (seat.color === 'red') {
                        if (!selectedSeats.includes(seat.id)) {
                            selectedSeats.push(seat.id);
                        }
                    } else {
                        selectedSeats = selectedSeats.filter(id => id !== seat.id);
                    }

                    // Atualizar o canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.fillStyle = 'green';
                    ctx.fillRect(200, 150, 400, 300);
                    drawSeats();
                }
            });
        });

        // Função para enviar os lugares selecionados para o servidor
        document.getElementById('buyTickets').addEventListener('click', function() {
            if (selectedSeats.length === 0) {
                alert("Por favor, selecione pelo menos um lugar antes de comprar.");
                return;
            }

            // Exibe os lugares selecionados no console para verificação
            console.log("Lugares selecionados:", selectedSeats);

            fetch('/buy-tickets', {
            method: 'POST',  // Certifique-se de que o método é POST
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ seats: selectedSeats })
            })

            .then(response => {
                // Verifica se a resposta foi bem-sucedida
                if (!response.ok) {
                    console.error('Erro na resposta do servidor:', response);
                    throw new Error('Erro ao processar o pedido');
                }
                return response.json();
            })
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


