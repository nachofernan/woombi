<?php

use App\Models\User;
use App\Models\Matche;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        \Database\Seeders\TournamentGroupSeeder::class,
        \Database\Seeders\TeamSeeder::class,
        \Database\Seeders\GroupTeamSeeder::class,
        \Database\Seeders\MatcheSeeder::class,
        \Database\Seeders\MatcheEliminatoriosSeeder::class
    ]);
});

test('un usuario puede ver sus predicciones', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/predicciones')
        ->assertOk();
});

test('un usuario puede cargar una predicción', function () {
    $user  = User::factory()->create();
    $match = Matche::where('stage', 'fase_grupos')->first();

    $this->actingAs($user)
        ->putJson("/api/predicciones/{$match->id}", [
            'predicted_home_score' => 2,
            'predicted_away_score' => 1,
        ])
        ->assertOk()
        ->assertJsonStructure(['id', 'predicted_home_score', 'predicted_away_score']);
});

test('no se puede predecir un partido ya iniciado', function () {
    $user  = User::factory()->create();
    $match = Matche::where('stage', 'fase_grupos')->first();
    $match->update(['match_date' => now()->subHour()]);

    $this->actingAs($user)
        ->putJson("/api/predicciones/{$match->id}", [
            'predicted_home_score' => 1,
            'predicted_away_score' => 0,
        ])
        ->assertForbidden();
});

test('un usuario puede cargar predicted_winner_team_id en eliminatorias', function () {
    $user  = User::factory()->create();
    $match = Matche::where('stage', 'octavos')->first(); // viene del MatcheEliminatoriosSeeder
    $team  = \App\Models\Team::first();

    $this->actingAs($user)
        ->putJson("/api/predicciones/{$match->id}", [
            'predicted_home_score'     => 1,
            'predicted_away_score'     => 1,
            'predicted_winner_team_id' => $team->id,
        ])
        ->assertOk()
        ->assertJsonStructure(['id', 'predicted_home_score', 'predicted_away_score', 'predicted_winner_team_id']);
});

test('predicted_winner_team_id debe ser un equipo existente', function () {
    $match = Matche::where('stage', 'fase_grupos')->first();
    $user  = User::factory()->create();

    $this->actingAs($user)
        ->putJson("/api/predicciones/{$match->id}", [
            'predicted_home_score'     => 1,
            'predicted_away_score'     => 0,
            'predicted_winner_team_id' => 99999,
        ])
        ->assertStatus(422);
});

test('predicted_winner_team_id es opcional en fase de grupos', function () {
    $match = Matche::where('stage', 'fase_grupos')->first();
    $user  = User::factory()->create();

    $this->actingAs($user)
        ->putJson("/api/predicciones/{$match->id}", [
            'predicted_home_score' => 2,
            'predicted_away_score' => 0,
        ])
        ->assertOk();
});