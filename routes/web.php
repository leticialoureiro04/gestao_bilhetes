<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\StadiumPlanController;
use App\Http\Controllers\StadiumController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\SeatTypeController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TicketController;

Route::get('/', function () {
    return view('welcome');
});

// Middleware para autenticação e verificação de sessão
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Rota de perfil do usuário
Route::get('/perfil', [UserController::class, 'profile'])->name('profile');

// Rotas de CRUD de utilizadores
Route::resource('users', UserController::class)->middleware('auth');

// Rota para configurar papéis e permissões iniciais
Route::get('/setup-roles', [SetupController::class, 'setupRolesAndPermissions']);

// Rotas para jogos e seleção de planta de estádio
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{game_id}/choose-plan', [StadiumPlanController::class, 'choosePlan'])->name('games.choose.plan');

// Rotas para selecionar e comprar bilhetes para lugares na planta do estádio
Route::get('/stadium-plan/{id}/seats', [StadiumPlanController::class, 'viewSeats'])->name('stadium.plan.seats');

// Rotas de compra de bilhetes e listagem de bilhetes do usuário
Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/buy-tickets', [TicketController::class, 'buyTickets'])->name('buy.tickets');
});

// Rotas de estádios e plantas de estádio
Route::resource('stadium_plans', StadiumPlanController::class)->middleware('auth');
Route::resource('stadiums', StadiumController::class)->middleware('auth');

// Rotas para tipos de lugares e lugares
Route::resource('seat_types', SeatTypeController::class)->middleware('auth');
Route::resource('seats', SeatController::class)->middleware('auth');

// Rotas para gerenciamento de papéis
Route::resource('roles', RoleController::class)->middleware('auth');
Route::get('/users/{user}/assign-role', [UserController::class, 'assignRoleForm'])->name('users.assign_role')->middleware('auth');
Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign_role.store')->middleware('auth');

// Rotas para equipas
Route::resource('teams', TeamController::class)->middleware('auth');

// Rotas de CRUD para jogos
Route::resource('games', GameController::class)->middleware('auth');

// Rotas para bilhetes com funcionalidades adicionais
Route::resource('tickets', TicketController::class)->middleware('auth');
Route::post('tickets/{ticket}/sell', [TicketController::class, 'sell'])->name('tickets.sell')->middleware('auth');
Route::post('tickets/{ticket}/reserve', [TicketController::class, 'reserve'])->name('tickets.reserve')->middleware('auth');

// Rota para obter os assentos disponíveis de um estádio (API)
Route::get('/seats/{stadium_id}', [SeatController::class, 'getSeatsByStadium'])->name('seats.byStadium');
Route::get('/api/seats/{stadium_id}', [SeatController::class, 'getAvailableSeats']);

// Rota para obter plantas de estádio específicas por estádio (para uso em JavaScript)
Route::get('/get-stadium-plans-by-stadium', [StadiumPlanController::class, 'getStadiumPlansByStadiumId'])->name('getStadiumPlansByStadium');
Route::get('/get-seats-by-type-and-plan', [TicketController::class, 'getSeatsByTypeAndPlan'])->name('getSeatsByTypeAndPlan');

// Rota para a visualização interativa da planta do estádio
Route::get('/stadium-plan/{id}', [StadiumPlanController::class, 'show'])->name('stadium.plan.show');

// Rota para configurar papéis e permissões (pode ser duplicada, verifique a necessidade)
Route::get('/setup-roles-permissions', [SetupController::class, 'setupRolesAndPermissions']);

