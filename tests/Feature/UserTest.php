<?php

use App\Models\User;

test('se puede ver el leaderboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/usuarios/leaderboard')
        ->assertOk()
        ->assertJsonStructure([['name', 'total_points']]);
});

test('se puede buscar usuarios', function () {
    $user = User::factory()->create(['name' => 'Juan Pérez']);
    $searcher = User::factory()->create();

    $this->actingAs($searcher)
        ->postJson('/api/usuarios/buscar', ['query' => 'Juan'])
        ->assertOk()
        ->assertJsonStructure([['id', 'name', 'total_points']]);
});

test('un usuario puede registrar su pronóstico de campeón', function () {
    $this->seed([
        \Database\Seeders\TeamSeeder::class,
    ]);
    $team = \App\Models\Team::first();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->putJson('/api/usuario/campeon', ['champion_team_id' => $team->id])
        ->assertOk()
        ->assertJson(['message' => 'Pronóstico de campeón guardado']);
});

test('no se puede modificar el campeón después del 11 de junio', function () {
    $this->seed([
        \Database\Seeders\TeamSeeder::class,
    ]);
    $team = \App\Models\Team::first();
    $user = User::factory()->create();

    \Carbon\Carbon::setTestNow('2026-06-12');

    $this->actingAs($user)
        ->putJson('/api/usuario/campeon', ['champion_team_id' => $team->id])
        ->assertForbidden();

    \Carbon\Carbon::setTestNow();
});