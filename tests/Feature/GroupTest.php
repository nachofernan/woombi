<?php

use App\Models\User;
use App\Models\Group;

test('un usuario puede crear un grupo', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/api/grupos', ['name' => 'Los Cracks'])
        ->assertStatus(201)
        ->assertJsonStructure(['id', 'name', 'invite_code']);
});

test('un usuario puede unirse a un grupo con código', function () {
    $owner = User::factory()->create();
    $user  = User::factory()->create();
    $group = Group::create([
        'name'        => 'Grupo Test',
        'owner_id'    => $owner->id,
        'invite_code' => 'ABC12345',
    ]);

    $this->actingAs($user)
        ->postJson('/api/grupos/unirse', ['invite_code' => 'ABC12345'])
        ->assertOk();
});

test('no se puede unir dos veces al mismo grupo', function () {
    $owner = User::factory()->create();
    $user  = User::factory()->create();
    $group = Group::create([
        'name'        => 'Grupo Test',
        'owner_id'    => $owner->id,
        'invite_code' => 'XYZ99999',
    ]);
    $group->users()->attach($user->id);

    $this->actingAs($user)
        ->postJson('/api/grupos/unirse', ['invite_code' => 'XYZ99999'])
        ->assertStatus(409);
});