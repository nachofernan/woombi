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

    // Partidos
    Route::get('/partidos',                [MatcheController::class, 'index']);
    Route::get('/partidos/{id}',           [MatcheController::class, 'show']);
    Route::get('/partidos/grupo/{grupo}',  [MatcheController::class, 'porGrupo']);
    Route::get('/partidos/stage/{stage}',  [MatcheController::class, 'porStage']);

    // Predicciones
    Route::get('/predicciones',            [PredictionController::class, 'index']);
    Route::put('/predicciones/{match_id}', [PredictionController::class, 'update']);

    // Grupos de amigos
    Route::get('/grupos',                  [GroupController::class, 'index']);
    Route::post('/grupos',                 [GroupController::class, 'store']);
    Route::get('/grupos/{id}',             [GroupController::class, 'show']);
    Route::post('/grupos/unirse',          [GroupController::class, 'unirse']);
    Route::get('/grupos/{id}/posiciones',  [GroupController::class, 'posiciones']);
    Route::post('/grupos/{id}/agregar',    [GroupController::class, 'agregarUsuario']);
    Route::delete('/grupos/{id}/quitar/{user_id}', [GroupController::class, 'quitarUsuario']);
    Route::delete('/grupos/{id}/salir',    [GroupController::class, 'salir']);

    // Usuarios
    Route::put('/usuario/campeon',         [UserController::class, 'setCampeon']);
    Route::get('/usuarios/leaderboard',    [UserController::class, 'leaderboard']);
    Route::post('/usuarios/buscar',        [UserController::class, 'buscar']);
});