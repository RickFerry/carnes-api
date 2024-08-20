<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarneController;

Route::post('/carnes', [CarneController::class, 'criarCarne']);
Route::get('/carnes/{id}/parcelas', [CarneController::class, 'recuperarParcelas']);
