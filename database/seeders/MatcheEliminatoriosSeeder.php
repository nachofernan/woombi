<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matche;

class MatcheEliminatoriosSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------
        // DIECISEISAVOS DE FINAL (73-88)
        // Sin source_match_id: los cruces se cargan a mano
        // cuando termina la fase de grupos
        // -------------------------
        $dieciseisavos = [
            ['match_number' => 73,  'match_date' => '2026-06-29 18:00:00'],
            ['match_number' => 74,  'match_date' => '2026-06-29 22:00:00'],
            ['match_number' => 75,  'match_date' => '2026-06-30 18:00:00'],
            ['match_number' => 76,  'match_date' => '2026-06-30 22:00:00'],
            ['match_number' => 77,  'match_date' => '2026-07-01 18:00:00'],
            ['match_number' => 78,  'match_date' => '2026-07-01 22:00:00'],
            ['match_number' => 79,  'match_date' => '2026-07-02 18:00:00'],
            ['match_number' => 80,  'match_date' => '2026-07-02 22:00:00'],
            ['match_number' => 81,  'match_date' => '2026-07-03 18:00:00'],
            ['match_number' => 82,  'match_date' => '2026-07-03 22:00:00'],
            ['match_number' => 83,  'match_date' => '2026-07-04 18:00:00'],
            ['match_number' => 84,  'match_date' => '2026-07-04 22:00:00'],
            ['match_number' => 85,  'match_date' => '2026-07-05 18:00:00'],
            ['match_number' => 86,  'match_date' => '2026-07-05 22:00:00'],
            ['match_number' => 87,  'match_date' => '2026-07-06 18:00:00'],
            ['match_number' => 88,  'match_date' => '2026-07-06 22:00:00'],
        ];

        foreach ($dieciseisavos as $data) {
            Matche::create([
                'match_number'         => $data['match_number'],
                'stage'                => 'dieciseisavos',
                'match_date'           => $data['match_date'],
                'status'               => 'pendiente',
                'tournament_group_id'  => null,
                'home_team_id'         => null,
                'away_team_id'         => null,
                'home_source_match_id' => null,
                'away_source_match_id' => null,
            ]);
        }

        // -------------------------
        // OCTAVOS DE FINAL (89-96)
        // Ganador de cada par de dieciseisavos
        // -------------------------
        $octavos = [
            [
                'match_number'         => 89,
                'match_date'           => '2026-07-09 22:00:00',
                'home_source_match_id' => $this->id(73),
                'away_source_match_id' => $this->id(74),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 90,
                'match_date'           => '2026-07-10 22:00:00',
                'home_source_match_id' => $this->id(75),
                'away_source_match_id' => $this->id(76),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 91,
                'match_date'           => '2026-07-11 18:00:00',
                'home_source_match_id' => $this->id(77),
                'away_source_match_id' => $this->id(78),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 92,
                'match_date'           => '2026-07-11 22:00:00',
                'home_source_match_id' => $this->id(79),
                'away_source_match_id' => $this->id(80),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 93,
                'match_date'           => '2026-07-12 18:00:00',
                'home_source_match_id' => $this->id(81),
                'away_source_match_id' => $this->id(82),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 94,
                'match_date'           => '2026-07-12 22:00:00',
                'home_source_match_id' => $this->id(83),
                'away_source_match_id' => $this->id(84),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 95,
                'match_date'           => '2026-07-13 18:00:00',
                'home_source_match_id' => $this->id(85),
                'away_source_match_id' => $this->id(86),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 96,
                'match_date'           => '2026-07-13 22:00:00',
                'home_source_match_id' => $this->id(87),
                'away_source_match_id' => $this->id(88),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
        ];

        foreach ($octavos as $data) {
            Matche::create(array_merge($data, [
                'stage'               => 'octavos',
                'status'              => 'pendiente',
                'tournament_group_id' => null,
                'home_team_id'        => null,
                'away_team_id'        => null,
            ]));
        }

        // -------------------------
        // CUARTOS DE FINAL (97-100)
        // Ganador de cada par de octavos
        // -------------------------
        $cuartos = [
            [
                'match_number'         => 97,
                'match_date'           => '2026-07-16 22:00:00',
                'home_source_match_id' => $this->id(89),
                'away_source_match_id' => $this->id(90),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 98,
                'match_date'           => '2026-07-17 22:00:00',
                'home_source_match_id' => $this->id(91),
                'away_source_match_id' => $this->id(92),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 99,
                'match_date'           => '2026-07-18 18:00:00',
                'home_source_match_id' => $this->id(93),
                'away_source_match_id' => $this->id(94),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 100,
                'match_date'           => '2026-07-18 22:00:00',
                'home_source_match_id' => $this->id(95),
                'away_source_match_id' => $this->id(96),
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
        // SEMIFINALES (101-102)
        // Ganador de cada par de cuartos
        // -------------------------
        $semis = [
            [
                'match_number'         => 101,
                'match_date'           => '2026-07-21 22:00:00',
                'home_source_match_id' => $this->id(97),
                'away_source_match_id' => $this->id(98),
                'home_source_result'   => 'ganador',
                'away_source_result'   => 'ganador',
            ],
            [
                'match_number'         => 102,
                'match_date'           => '2026-07-22 22:00:00',
                'home_source_match_id' => $this->id(99),
                'away_source_match_id' => $this->id(100),
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
        // TERCER PUESTO (103)
        // Perdedores de ambas semis
        // -------------------------
        Matche::create([
            'match_number'         => 103,
            'stage'                => 'tercero',
            'match_date'           => '2026-07-25 18:00:00',
            'status'               => 'pendiente',
            'tournament_group_id'  => null,
            'home_team_id'         => null,
            'away_team_id'         => null,
            'home_source_match_id' => $this->id(101),
            'away_source_match_id' => $this->id(102),
            'home_source_result'   => 'perdedor',
            'away_source_result'   => 'perdedor',
        ]);

        // -------------------------
        // FINAL (104)
        // Ganadores de ambas semis
        // -------------------------
        Matche::create([
            'match_number'         => 104,
            'stage'                => 'final',
            'match_date'           => '2026-07-26 22:00:00',
            'status'               => 'pendiente',
            'tournament_group_id'  => null,
            'home_team_id'         => null,
            'away_team_id'         => null,
            'home_source_match_id' => $this->id(101),
            'away_source_match_id' => $this->id(102),
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