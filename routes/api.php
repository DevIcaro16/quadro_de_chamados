<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TesteController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\UserChamadosController;
use App\Http\Controllers\Api\V1\ChamadosController;
use App\Http\Controllers\ClientesController;

// Usuarios
Route::get('/users',[UserController::class, 'index']);
Route::get('/users/{user}',[UserController::class, 'show']);

// Clientes
Route::get('/clientes', [ClientesController::class, 'index']);
Route::get('/clientes/{cliente}', [ClientesController::class, 'show']);

// Chamados
Route::get('/listar-chamados',[ChamadosController::class, 'index']);
Route::get('/listar-chamados/{chamados}',[ChamadosController::class, 'show']);
Route::get('/total-chamados', [ChamadosController::class, 'contarChamados']);
Route::get('/paginacao-chamados', [ChamadosController::class, 'getPaginatedChamados']);





// // Inserir chamados
// Route::post('/listar-chamados',[ChamadosController::class, 'store']);
// // Atualizar chamados
// Route::put('/listar-chamados/{chamados}',[ChamadosController::class, 'update']);
// // Deletar chamados
// Route::delete('/listar-chamados/{chamados}', [ChamadosController::class, 'destroy']);