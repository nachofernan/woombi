<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MatcheController;
use App\Http\Controllers\Api\PredictionController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TeamController;

// Públicas
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Autenticadas
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Equipos
    Route::get('/equipos', [TeamController::class, 'index']);
    Route::get('/equipos/{id}', [TeamController::class, 'show']);

    // Partidos
    Route::get('/partidos',                [MatcheController::class, 'index']);
    Route::get('/partidos/{id}',           [MatcheController::class, 'show']);
    Route::get('/partidos/grupo/{grupo}',  [MatcheController::class, 'porGrupo']);
    Route::get('/partidos/stage/{stage}',  [MatcheController::class, 'porStage']);

    // Predicciones
    Route::get('/predicciones',            [PredictionController::class, 'index']);
    Route::get('/predicciones/{match_id}', [PredictionController::class, 'show']);
    Route::put('/predicciones/{match_id}', [PredictionController::class, 'update']);

    // Grupos de amigos
    Route::get('/grupos',                  [GroupController::class, 'index']);
    Route::post('/grupos',                 [GroupController::class, 'store']);
    Route::post('/grupos/unirse',          [GroupController::class, 'unirse']);
    Route::get('/grupos/{id}',             [GroupController::class, 'show']);
    Route::get('/grupos/{id}/posiciones',  [GroupController::class, 'posiciones']);
    Route::post('/grupos/{id}/agregar',    [GroupController::class, 'agregarUsuario']);
    Route::post('/grupos/{id}/agregar/mail',    [GroupController::class, 'agregarPorMail']);
    Route::delete('/grupos/{id}/quitar/{user_id}', [GroupController::class, 'quitarUsuario']);
    Route::delete('/grupos/{id}/salir',    [GroupController::class, 'salir']);
    Route::delete('/grupos/{id}',              [GroupController::class, 'destroy']);

    // Usuarios
    Route::put('/usuario/campeon',         [UserController::class, 'setCampeon']);
    Route::put('/usuario/update',         [UserController::class, 'update']);
    Route::get('/usuarios/leaderboard',    [UserController::class, 'leaderboard']);
    Route::post('/usuarios/buscar',        [UserController::class, 'buscar']);
    Route::post('/usuarios/buscar/mail',   [UserController::class, 'buscarPorMail']);
    Route::get('/usuarios/{id}',           [UserController::class, 'show']);
});