<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GESFaturacaoAPIController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;


Route::post('/login', [GESFaturacaoAPIController::class, 'loginAPI']);

Route::get('/validate-token', [GESFaturacaoAPIController::class, 'validateToken']);

Route::get('/get-token', [GESFaturacaoAPIController::class, 'getToken']);

Route::post('/create-invoice', [GESFaturacaoAPIController::class, 'createInvoice']);

Route::post('/login', [AuthController::class, 'login']);
Route::get('/add-client/{id}', [GESFaturacaoAPIController::class, 'addClient']);









