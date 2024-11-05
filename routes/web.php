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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Rota de perfil
Route::get('/perfil', [UserController::class, 'profile'])->name('profile');

// Aqui adicionas a rota para o CRUD de utilizadores
Route::resource('users', UserController::class);

// Rota para configurar papéis e permissões iniciais
Route::get('/setup-roles', [SetupController::class, 'setupRolesAndPermissions']);

Route::resource('stadium_plans', StadiumPlanController::class);

Route::resource('stadiums', StadiumController::class);


Route::resource('seat_types', SeatTypeController::class);

Route::resource('seats', SeatController::class)->middleware('auth');

Route::resource('roles', RoleController::class)->middleware('auth');


Route::get('/users/{user}/assign-role', [UserController::class, 'assignRoleForm'])->name('users.assign_role')->middleware('auth');
Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign_role.store')->middleware('auth');

Route::resource('teams', TeamController::class)->middleware('auth');

// Rotas para jogos
Route::resource('games', GameController::class)->middleware('auth');

// Rotas para bilhetes
Route::resource('tickets', TicketController::class)->middleware('auth');
Route::post('tickets/{ticket}/sell', [TicketController::class, 'sell'])->name('tickets.sell')->middleware('auth');
Route::post('tickets/{ticket}/reserve', [TicketController::class, 'reserve'])->name('tickets.reserve')->middleware('auth');

Route::get('/seats/{stadium_id}', [SeatController::class, 'getSeatsByStadium'])->name('seats.byStadium');
Route::get('/api/seats/{stadium_id}', [SeatController::class, 'getAvailableSeats']);
Route::get('/get-seats', [TicketController::class, 'getAvailableSeats'])->name('getSeats');
Route::get('/get-seats-by-type', [TicketController::class, 'getSeatsByType'])->name('getSeatsByType');


Route::get('/get-seats', [TicketController::class, 'getSeatsByType'])->name('getSeatsByType');


Route::get('/get-stadium-plans', [StadiumPlanController::class, 'getStadiumPlans'])->name('getStadiumPlans');
Route::get('/get-seats-by-type-and-plan', [TicketController::class, 'getSeatsByTypeAndPlan'])->name('getSeatsByTypeAndPlan');


Route::get('/get-stadium-plans', [StadiumPlanController::class, 'getStadiumPlans'])->name('getStadiumPlans');

Route::get('/get-stadium-plans-by-stadium', [StadiumPlanController::class, 'getStadiumPlansByStadiumId'])->name('getStadiumPlansByStadium');

Route::get('/get-seats-by-type-and-plan', [TicketController::class, 'getSeatsByTypeAndPlan'])->name('getSeatsByTypeAndPlan');
Route::get('/get-stadium-plans-by-stadium', [StadiumPlanController::class, 'getStadiumPlansByStadiumId'])->name('getStadiumPlansByStadium');


Route::get('/setup-roles-permissions', [App\Http\Controllers\SetupController::class, 'setupRolesAndPermissions']);


