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