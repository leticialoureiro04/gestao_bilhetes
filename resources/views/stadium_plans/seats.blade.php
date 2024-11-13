@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Escolha um Lugar na Planta: {{ $stadiumPlan->name }}</h3>
    <canvas id="stadiumCanvas" width="800" height="600" style="border:1px solid #000000;"></canvas>
    <button id="buyTickets" class="btn btn-primary mt-3" disabled>Comprar Bilhetes</button>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('stadiumCanvas');
        const ctx = canvas.getContext('2d');
        const selectedSeats = [];

        // Desenhar o retângulo do campo (campo de jogo) em verde claro
        ctx.fillStyle = '#90EE90'; // Verde claro
        ctx.fillRect(100, 100, 600, 400); 

        // Assumindo que os lugares foram passados do controlador como uma variável JavaScript
        const seats = @json($seats);

        // Função para desenhar os lugares como bolinhas
        function drawSeats() {
            seats.forEach(seat => {
                ctx.fillStyle = seat.status === 'vendido' ? 'red' : 'green'; // Vermelho para vendidos, verde para livres
                ctx.beginPath();
                ctx.arc(seat.x_position, seat.y_position, 10, 0, 2 * Math.PI); // Desenha um círculo para cada lugar
                ctx.fill();
                ctx.closePath();
            });
        }

        // Desenhar os lugares inicialmente
        drawSeats();

        // Detectar clique e alternar a cor do lugar selecionado
        canvas.addEventListener('click', function(event) {
            const rect = canvas.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;

            seats.forEach(seat => {
                if (Math.sqrt((x - seat.x_position) ** 2 + (y - seat.y_position) ** 2) < 10) {
                    if (seat.status !== 'vendido') {
                        seat.color = seat.color === 'green' ? 'blue' : 'green'; // Alterna entre verde e azul

                        if (seat.color === 'blue') {
                            selectedSeats.push(seat.id);
                        } else {
                            const index = selectedSeats.indexOf(seat.id);
                            if (index > -1) {
                                selectedSeats.splice(index, 1);
                            }
                        }

                        // Redesenha o campo e os lugares
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.fillStyle = '#90EE90'; // Redesenha o campo em verde claro
                        ctx.fillRect(100, 100, 600, 400);
                        drawSeats();
                    }
                }
            });

            // Habilitar o botão de compra se houver lugares selecionados
            document.getElementById('buyTickets').disabled = selectedSeats.length === 0;
        });

        // Função para processar a compra dos lugares selecionados
        document.getElementById('buyTickets').addEventListener('click', function() {
            if (selectedSeats.length > 0) {
                fetch('{{ route('buy.tickets') }}', {
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
                        alert('Erro ao comprar bilhetes.');
                    }
                })
                .catch(error => console.error('Erro na compra:', error));
            }
        });
    });
</script>
@endpush






