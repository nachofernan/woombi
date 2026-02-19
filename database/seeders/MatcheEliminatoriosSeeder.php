<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matche;

class MatcheEliminatoriosSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------
        // OCTAVOS DE FINAL (73-80)
        // Sin source_match_id: los cruces se cargan a mano
        // cuando termina la fase de grupos
        // -------------------------
        $octavos = [
            ['match_number' => 73, 'match_date' => '2026-07-01 18:00:00'],
            ['match_number' => 74, 'match_date' => '2026-07-01 22:00:00'],
            ['match_number' => 75, 'match_date' => '2026-07-02 18:00:00'],
            ['match_number' => 76, 'match_date' => '2026-07-02 22:00:00'],
            ['match_number' => 77, 'match_date' => '2026-07-03 18:00:00'],
            ['match_number' => 78, 'match_date' => '2026-07-03 22:00:00'],
            ['match_number' => 79, 'match_date' => '2026-07-04 18:00:00'],
            ['match_number' => 80, 'match_date' => '2026-07-04 22:00:00'],
        ];

        foreach ($octavos as $data) {
            Matche::create([
                'match_number'        => $data['match_number'],
                'stage'               => 'octavos',
                'match_date'          => $data['match_date'],
                'status'              => 'pendiente',
                'tournament_group_id' => null,
                'home_team_id'        => null,
                'away_team_id'        => null,
                'home_source_match_id'=> null,
                'away_source_match_id'=> null,
            ]);
        }

        // -------------------------
        // CUARTOS DE FINAL (81-84)
        // Ganador de cada par de octavos
        // -------------------------
        $cuartos = [
            [
                'match_number'         => 81,
                'match_date'           => '2026-07-08 22:00:00',
                'home_source_match_id' => $this->id(73),
                'away_source_match_id' => $this->id(74),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 82,
                'match_date'           => '2026-07-09 22:00:00',
                'home_source_match_id' => $this->id(75),
                'away_source_match_id' => $this->id(76),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 83,
                'match_date'           => '2026-07-10 22:00:00',
                'home_source_match_id' => $this->id(77),
                'away_source_match_id' => $this->id(78),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 84,
                'match_date'           => '2026-07-11 22:00:00',
                'home_source_match_id' => $this->id(79),
                'away_source_match_id' => $this->id(80),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
        ];

        foreach ($cuartos as $data) {
            Matche::create(array_merge($data, [
                'stage'               => 'cuartos',
                'status'              => 'pendiente',
                'tournament_group_id' => null,
                'home_team_id'        => null,
                'away_team_id'        => null,
            ]));
        }

        // -------------------------
        // SEMIFINALES (85-86)
        // Ganador de cada par de cuartos
        // -------------------------
        $semis = [
            [
                'match_number'         => 85,
                'match_date'           => '2026-07-14 22:00:00',
                'home_source_match_id' => $this->id(81),
                'away_source_match_id' => $this->id(82),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 86,
                'match_date'           => '2026-07-15 22:00:00',
                'home_source_match_id' => $this->id(83),
                'away_source_match_id' => $this->id(84),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
        ];

        foreach ($semis as $data) {
            Matche::create(array_merge($data, [
                'stage'               => 'semis',
                'status'              => 'pendiente',
                'tournament_group_id' => null,
                'home_team_id'        => null,
                'away_team_id'        => null,
            ]));
        }

        // -------------------------
        // TERCER PUESTO (87)
        // Perdedores de ambas semis
        // -------------------------
        Matche::create([
            'match_number'         => 87,
            'stage'                => 'tercero',
            'match_date'           => '2026-07-18 22:00:00',
            'status'               => 'pendiente',
            'tournament_group_id'  => null,
            'home_team_id'         => null,
            'away_team_id'         => null,
            'home_source_match_id' => $this->id(85),
            'away_source_match_id' => $this->id(86),
            'home_source_result'   => 'perdedor',
            'away_source_result'   => 'perdedor',
        ]);

        // -------------------------
        // FINAL (88)
        // Ganadores de ambas semis
        // -------------------------
        Matche::create([
            'match_number'         => 88,
            'stage'                => 'final',
            'match_date'           => '2026-07-19 22:00:00',
            'status'               => 'pendiente',
            'tournament_group_id'  => null,
            'home_team_id'         => null,
            'away_team_id'         => null,
            'home_source_match_id' => $this->id(85),
            'away_source_match_id' => $this->id(86),
            'home_source_result'   => 'ganador',
            'away_source_result'   => 'ganador',
        ]);
    }

    // Helper para no hardcodear IDs: busca por match_number
    private function id(int $matchNumber): int
    {
        return Matche::where('match_number', $matchNumber)->value('id');
    }
}