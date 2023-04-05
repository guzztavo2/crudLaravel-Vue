<?php

use App\Http\Controllers\informacaoController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Middleware\HandleCors;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//header('Access-Control-Allow-Origin: http://localhost:8081');

Route::controller(userController::class)->middleware([HandleCors::class])->group(function () {
    Route::post('/login', 'login');
    Route::post('/registro', 'registro');
    Route::post('/verificarRegistroNomeUsuario', 'verificarRegistroNomeUsuario');
});
Route::controller(userController::class)->middleware(['auth:sanctum', HandleCors::class])->group(function () {
    Route::get('/user', 'user');
    Route::post('/logout', 'logout');
    Route::post('/verificarSenha', 'verificarSenha');
    Route::post('/trocarSenha', 'trocarSenha');
    Route::post('/trocarNomeUsuario', 'trocarNomeUsuario');
});
Route::controller(informacaoController::class)->middleware([HandleCors::class])->group(function () {
    Route::get('/informacao', 'informacao');
    Route::get('/informacao/{id}', 'buscarInformacao');
    Route::delete('/informacao/{id}', 'deletarInformacao');
    Route::delete('/informacao', 'deletarInformacoes');

    Route::put('/editarInformacao/{id}', 'editarInformacao');
});
