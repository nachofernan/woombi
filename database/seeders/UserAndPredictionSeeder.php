<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Matche;
use App\Models\Prediction;

class UserAndPredictionSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios de prueba
        $users = [
            ['name' => 'Admin',   'email' => 'admin@prode.com',   'password' => Hash::make('password'), 'role' => 'admin', 'total_points' => 0],
            ['name' => 'Juan',    'email' => 'juan@prode.com',    'password' => Hash::make('password'), 'role' => 'jugador', 'total_points' => 0],
            ['name' => 'María',   'email' => 'maria@prode.com',   'password' => Hash::make('password'), 'role' => 'jugador', 'total_points' => 0],
            ['name' => 'Carlos',  'email' => 'carlos@prode.com',  'password' => Hash::make('password'), 'role' => 'jugador', 'total_points' => 0],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }

        // Predicciones random para cada usuario en todos los partidos
        $allUsers   = User::all();
        $allMatches = Matche::all();

        foreach ($allUsers as $user) {
            foreach ($allMatches as $match) {
                Prediction::create([
                    'user_id'              => $user->id,
                    'match_id'             => $match->id,
                    'predicted_home_score' => rand(0, 4),
                    'predicted_away_score' => rand(0, 4),
                    'points'               => null,
                ]);
            }
        }
    }
}
