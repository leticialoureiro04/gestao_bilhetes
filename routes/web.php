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
use App\Http\Controllers\StandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\EmailTestController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\GESFaturacaoAPIController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/', function () {
    // Verifica se o usuário está logado
    if (Auth::check()) {
        // Redireciona para o dashboard se estiver logado
        return redirect()->route('dashboard');
    }
    // Se não estiver logado, redireciona para a página de login
    return redirect()->route('login');
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
    Route::post('/buy-tickets', [TicketController::class, 'buyTickets'])->name('tickets.buy');
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

Route::get('/stands/configure/{stadium_id}', [StandController::class, 'configure'])->name('stands.configure');
Route::post('/stands/store', [StandController::class, 'store'])->name('stands.store');
Route::put('/stands/{stand}', [StandController::class, 'update'])->name('stands.update');
Route::post('/stands/{stand}/seats', [StandController::class, 'storeSeats'])->name('stands.storeSeats');
Route::get('/stadiums/{stadium_id}/stands', [StandController::class, 'show'])->name('stands.show');

Route::get('/seats', [SeatController::class, 'index'])->name('seats.index');

Route::get('/stadiums/{stadium}/view', [StadiumController::class, 'viewLayout'])->name('stadiums.view');
Route::get('/stadiums/{stadium}/view', [StadiumController::class, 'view'])->name('stadiums.view');
Route::post('/stadiums/{stadium}/view', [StadiumController::class, 'view'])->name('stadiums.view');
Route::get('/stands/{stand}', [StandController::class, 'showSeats'])->name('stands.showSeats');
//Route::post('/stadiums/view/{id}', [StadiumController::class, 'view'])->name('stadiums.view');
Route::get('/stadiums/view/{id}', [StadiumController::class, 'view'])->name('stadiums.view');
Route::match(['get', 'post'], '/stadiums/{stadium}/view', [StadiumController::class, 'view'])->name('stadiums.view');
Route::get('/stands/{stand}/seats', 'StandController@getSeats');

Route::get('/stands/{stand}/seats', [StandController::class, 'getSeats']);

Route::get('/stands/{stand}/view', [StandController::class, 'view'])->name('stands.view');
Route::get('/stadiums/{id}/view', [StadiumController::class, 'view'])->name('stadiums.view');
Route::get('/stadiums/{stadium}/view-layout', [StadiumController::class, 'viewLayout'])->name('stadiums.viewLayout');
Route::post('/tickets/buy', [TicketController::class, 'buyTickets'])->name('tickets.buy');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/export-map', [ExportController::class, 'exportMap'])->name('export.map');
Route::get('/relatorios', [App\Http\Controllers\ExportController::class, 'show'])->name('relatorios.index');
Route::get('/export-map', [App\Http\Controllers\ExportController::class, 'exportMap'])->name('export.map');




// Página para exibir os filtros e opções de exportação
Route::get('/relatorios', [ExportController::class, 'show'])->name('relatorios.index');

// Rota para processar a exportação
Route::post('/relatorios/export', [ExportController::class, 'exportMap'])->name('relatorios.export');

Route::get('/bancadas/{stadium}', [ExportController::class, 'getBancadas']);

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'pt'])) {
        session::put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Troca de idioma
Route::get('lang/{locale}', [LanguageController::class, 'switchLang'])->name('lang.switch');

// Dashboard com verificação de idioma
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');



Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');


Route::post('/invoices/{id}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');


Route::get('/reencriptar-dados', function () {
    foreach (User::all() as $user) {
        $user->update([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
    return 'Dados reencriptados com sucesso!';
});
