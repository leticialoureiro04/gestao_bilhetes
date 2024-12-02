<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GESFaturacaoAPIController;
use App\Http\Controllers\AuthController;


Route::post('/login', [GESFaturacaoAPIController::class, 'loginAPI']);

Route::post('/validate-token', [GESFaturacaoAPIController::class, 'validateToken']);

Route::get('/get-token', [GESFaturacaoAPIController::class, 'getToken']);

Route::post('/create-invoice', [GESFaturacaoAPIController::class, 'createInvoice']);

Route::post('/login', [AuthController::class, 'login']);

