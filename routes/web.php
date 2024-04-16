<?php

use App\Models\User;
use App\Models\Cliente;
use App\Models\Chamados;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apiController;
use App\Http\Controllers\AuthController;
use App\Http\Resources\ClientesResource;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\SenhaController;
use App\Http\Controllers\UserChamadosController;
use App\Http\Controllers\Api\V1\EditIndController;
use App\Http\Controllers\Api\V1\ChamadosController;
use App\Http\Controllers\DetalhamentoLogController;

// Rota para exibir o formulário de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// Rota para processar o formulário de login
Route::post('/login', [AuthController::class, 'login']);

Route::get('/update_clientes', function () {

    return view('update_client_images');
});



Route::middleware('auth:sanctum')->group(function () {  

    //API chamados individuais:
    Route::get('/user-chamados/{userId}', [UserChamadosController::class, 'getUserChamados']);
    
    Route::get('/', function () {

        $chamados = Chamados::all();

        return view('home')->with('chamados', $chamados);
    });

    Route::get('/register', function () {

    $chamados = Chamados::all();
    
    return view('auth.register', compact('chamados'));
});


    Route::get('/meus_chamados', function () {

        $chamado = Chamados::all();
        
        return view('meus_chamados', compact('chamado'));
    });

    Route::get('/perfil', function () {
        $users = User::all();
        return view('perfil', compact('users'));
    });

    // Salvar detalhamentos no banco de dados:
    Route::post('/chamados/salvarDt/{chamado}', [ChamadosController::class, 'detalhamento'])->name('enviar-detalhamento');
    // Api listando os detalhamentos:
    Route::get('/api/v1/listar-detalhamentos', [DetalhamentoLogController::class, 'index']);
    Route::get('/api/v1/listar-detalhamentos/{chamados_id}', [DetalhamentoLogController::class, 'show']);

    // ASSUMIR CHAMADO
    Route::get('/chamados/assumir/{chamado}', [ChamadosController::class, 'assumirChamado']);
    // Devolver chamados:
    Route::delete('/chamados/leave/{chamado}', [ChamadosController::class, 'leaveChamado']);
    // Finalizar chamados:
    Route::post('/chamados/finalizar/{chamado}',[ChamadosController::class, 'finalizarChamado']);
    // Reabrir chamados:
    Route::post('/chamados/reabrir/{chamado}',[ChamadosController::class, 'ReabrirChamado']);
    // Editar chamados:
    Route::put('/edit/{chamado}', [ChamadosController::class, 'editChamado']);

    // EDITAR REGISTROS INDIVIDUAIS DO USUÁRIO
    Route::get('/perfil/edit', [EditIndController::class, 'edit'])->name('users.editindividual');
    Route::put('/perfil/update', [EditIndController::class, 'update'])->name('users.updateindividual');
    Route::post('/perfil/remove-image', [EditIndController::class, 'removeImage'])->name('users.removeimage');

    // REDEFINIR SENHA INDIVIDUAL DO USUÁRIO
    Route::put('/perfil/redefinir-senha', [EditIndController::class, 'redefinirSenha'])->name('users.redefinirsenha');

});


Route::middleware(['auth', 'nivel:2'])->group(function () {

    // Desabilitar chamados:
    Route::get('/chamados/desabilitar/{chamado}', [ChamadosController::class, 'desabilitarChamados']);

    // Reabilitar chamados:
    Route::get('/chamados/habilitar/{chamado}', [ChamadosController::class, 'reabilitarChamados']);

    // Excluir chamados:
    Route::delete('/api/v1/listar-chamados/{chamado}', [ChamadosController::class, 'destroy']);

    Route::get('/clientes', function (){
        $clientes = Cliente::all();
        return view('lista-clientes', compact('clientes'));
    });

    // Registrar clientes
    Route::get('/clientes', [ClientesController::class, 'showRegistrationForm'])->name('register_clientes');
    Route::post('/clientes', [ClientesController::class, 'register_clientes']);

    // Editar clientes
    Route::put('/clientes/{cliente}', [ClientesController::class, 'update'])->name('clientes.update');
    Route::get('/clientes/{cliente}/edit', [ClientesController::class, 'edit'])->name('clientes.edit');
    //Route::get('/obterClienteId/{cliente}', 'ClienteController@getClienteId');

    // Editar chamados
    Route::get('/chamados/{chamado}/edit', [ChamadosController::class, 'edit'])->name('chamados.edit');
    Route::put('/chamados/{chamado}', [ChamadosController::class, 'editarChamados'])->name('chamados.update');

    // Excluir clientes
    Route::delete('/clientes/{cliente}', [ClientesController::class, 'destroy'])->name('clientes.destroy');

    
});

Route::get('/users', function () {
    $users = User::all();
    return view('lista-usuarios', compact('users'));
});

// Rota para exibir o formulário de registro
Route::get('/users', [AuthController::class, 'showRegistrationForm'])->name('register');
// Rota para processar o formulário de registro
Route::post('/users', [AuthController::class, 'register']);

// EDITAR REGISTROS DO USUÁRIO
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

// REDEFINIR SENHA
Route::put('/editSenha/{user}', [SenhaController::class, 'editSenhaUser']);

// Route::get('/users/{user}/editsenha', [SenhaController::class, 'edit'])->name('users.editsenha');
// Route::put('/users/{user}/updatesenha', [SenhaController::class, 'update'])->name('users.updatesenha');

// DELETAR USUARIOS
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CRIAR CHAMADOS

Route::get('/listar-chamados',[ChamadosController::class, 'store'])->name('create');
Route::post('/listar-chamados',[ChamadosController::class, 'store']);

// Em routes/web.php
Route::get('/get-cliente-info/{codigo}', [ChamadosController::class, 'getClienteInfo']);
Route::get('/get-id/{codigo}', [ChamadosController::class, 'getId']);