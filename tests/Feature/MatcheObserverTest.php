<?php

use App\Models\Matche;
use App\Models\Prediction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        \Database\Seeders\TournamentGroupSeeder::class,
        \Database\Seeders\TeamSeeder::class,
        \Database\Seeders\GroupTeamSeeder::class,
        \Database\Seeders\MatcheSeeder::class,
        \Database\Seeders\MatcheEliminatoriosSeeder::class,
    ]);
});

// Helper para crear usuario con predicción sobre un partido
function crearUsuarioConPrediccion(Matche $match, array $prediccion): User
{
    $user = User::factory()->create();
    Prediction::updateOrCreate(
        ['user_id' => $user->id, 'match_id' => $match->id],
        $prediccion
    );
    return $user;
}

// Helper para finalizar un partido y refrescar el usuario
function finalizarPartido(Matche $match, int $home, int $away, ?int $penaltyWinnerId = null): void
{
    $match->update([
        'home_score'        => $home,
        'away_score'        => $away,
        'penalty_winner_id' => $penaltyWinnerId,
        'status'            => 'finalizado',
    ]);
}

// -------------------------------------------------------
// FASE DE GRUPOS
// -------------------------------------------------------

test('score exacto en grupos suma 5 puntos', function () {
    $match = Matche::where('stage', 'fase_grupos')->first();
    $user  = crearUsuarioConPrediccion($match, [
        'predicted_home_score' => 2,
        'predicted_away_score' => 1,
    ]);

    finalizarPartido($match, 2, 1);

    expect($user->fresh()->total_points)->toBe(5);
});

test('solo resultado correcto en grupos suma 3 puntos', function () {
    $match = Matche::where('stage', 'fase_grupos')->first();
    $user  = crearUsuarioConPrediccion($match, [
        'predicted_home_score' => 3,
        'predicted_away_score' => 0,
    ]);

    finalizarPartido($match, 2, 1); // mismo resultado, distinto score

    expect($user->fresh()->total_points)->toBe(3);
});

test('un score acertado sin resultado correcto en grupos suma 1 punto', function () {
    $match = Matche::where('stage', 'fase_grupos')->first();
    $user  = crearUsuarioConPrediccion($match, [
        'predicted_home_score' => 2,
        'predicted_away_score' => 3, // pronosticó ganador visitante
    ]);

    finalizarPartido($match, 2, 1); // ganó local

    expect($user->fresh()->total_points)->toBe(1);
});

test('pronóstico totalmente errado en grupos suma 0 puntos', function () {
    $match = Matche::where('stage', 'fase_grupos')->first();
    $user  = crearUsuarioConPrediccion($match, [
        'predicted_home_score' => 0,
        'predicted_away_score' => 3,
    ]);

    finalizarPartido($match, 2, 1);

    expect($user->fresh()->total_points)->toBe(0);
});

// -------------------------------------------------------
// FASE MEDIA (16avos)
// -------------------------------------------------------

test('score exacto en fase media suma 10 puntos', function () {
    $match = Matche::where('stage', 'dieciseisavos')->first();
    $match->update(['home_team_id' => Team::first()->id, 'away_team_id' => Team::skip(1)->first()->id]);

    $user = crearUsuarioConPrediccion($match, [
        'predicted_home_score'     => 2,
        'predicted_away_score'     => 1,
        'predicted_winner_team_id' => $match->home_team_id,
    ]);

    finalizarPartido($match, 2, 1);

    expect($user->fresh()->total_points)->toBe(10);
});

test('solo resultado correcto en fase media suma 4 puntos', function () {
    $match = Matche::where('stage', 'dieciseisavos')->first();
    $match->update(['home_team_id' => Team::first()->id, 'away_team_id' => Team::skip(1)->first()->id]);

    $user = crearUsuarioConPrediccion($match, [
        'predicted_home_score'     => 3,
        'predicted_away_score'     => 0,
        'predicted_winner_team_id' => $match->home_team_id,
    ]);

    finalizarPartido($match, 2, 1);

    expect($user->fresh()->total_points)->toBe(6); // resultado (4) + bonus quien pasa (2)
});

test('empate con penalty winner correcto en fase media suma 10 puntos', function () {
    $match = Matche::where('stage', 'dieciseisavos')->first();
    $match->update(['home_team_id' => Team::first()->id, 'away_team_id' => Team::skip(1)->first()->id]);

    $user = crearUsuarioConPrediccion($match, [
        'predicted_home_score'     => 1,
        'predicted_away_score'     => 1,
        'predicted_winner_team_id' => $match->home_team_id,
    ]);

    finalizarPartido($match, 1, 1, $match->home_team_id);

    expect($user->fresh()->total_points)->toBe(10);
});

test('empate con penalty winner errado en fase media suma 8 puntos', function () {
    $match = Matche::where('stage', 'dieciseisavos')->first();
    $match->update(['home_team_id' => Team::first()->id, 'away_team_id' => Team::skip(1)->first()->id]);

    $user = crearUsuarioConPrediccion($match, [
        'predicted_home_score'     => 1,
        'predicted_away_score'     => 1,
        'predicted_winner_team_id' => $match->away_team_id, // erró quién pasa
    ]);

    finalizarPartido($match, 1, 1, $match->home_team_id);

    expect($user->fresh()->total_points)->toBe(8); // 2+2+4, sin bonus
});

// -------------------------------------------------------
// FASE FINAL (semis)
// -------------------------------------------------------

test('score exacto en semis suma 13 puntos', function () {
    $match = Matche::where('stage', 'semis')->first();
    $match->update(['home_team_id' => Team::first()->id, 'away_team_id' => Team::skip(1)->first()->id]);

    $user = crearUsuarioConPrediccion($match, [
        'predicted_home_score'     => 2,
        'predicted_away_score'     => 1,
        'predicted_winner_team_id' => $match->home_team_id,
    ]);

    finalizarPartido($match, 2, 1);

    expect($user->fresh()->total_points)->toBe(13);
});

// -------------------------------------------------------
// CAMPEÓN
// -------------------------------------------------------

test('acertar campeón antes del mundial suma 50 puntos', function () {
    $this->seed(\Database\Seeders\MatcheEliminatoriosSeeder::class);

    $final = Matche::where('stage', 'final')->first();
    $final->update([
        'home_team_id' => Team::first()->id,
        'away_team_id' => Team::skip(1)->first()->id,
    ]);

    $user = User::factory()->create([
        'champion_team_id'    => Team::first()->id,
        'champion_updated_at' => now()->subMonth(), // antes del mundial
    ]);

    finalizarPartido($final, 2, 1);

    expect($user->fresh()->total_points)->toBe(50);
});