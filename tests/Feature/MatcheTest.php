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
    ]);
});

test('se puede listar todos los partidos', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/partidos')
        ->assertOk()
        ->assertJsonStructure([['id', 'stage', 'status']]);
});

test('se puede ver un partido por id', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/partidos/1')
        ->assertOk()
        ->assertJsonStructure(['id', 'stage', 'status']);
});

test('se pueden pedir partidos por grupo', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/partidos/grupo/A')
        ->assertOk();
});

test('se pueden pedir partidos por stage', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/partidos/stage/fase_grupos')
        ->assertOk();
});

test('no se puede acceder sin autenticación', function () {
    $this->getJson('/api/partidos')->assertUnauthorized();
});