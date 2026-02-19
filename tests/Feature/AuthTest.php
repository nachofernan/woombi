<?php

use App\Models\User;

test('un usuario puede registrarse', function () {
    $response = $this->postJson('/api/register', [
        'name'                  => 'Test User',
        'email'                 => 'test@prode.com',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)->assertJsonStructure(['token', 'user']);
});

test('un usuario puede loguearse', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);

    $response = $this->postJson('/api/login', [
        'email'    => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()->assertJsonStructure(['token', 'user']);
});

test('un usuario puede cerrar sesión', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/api/logout')->assertOk();
});