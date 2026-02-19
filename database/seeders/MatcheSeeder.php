<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matche;
use App\Models\Team;
use App\Models\TournamentGroup;

class MatcheSeeder extends Seeder
{
    public function run(): void
    {
        // Helper para resolver IDs
        $team  = fn($code) => Team::where('fifa_code', $code)->value('id');
        $group = fn($name) => TournamentGroup::where('name', $name)->value('id');

        $matches = [

            // -------------------------
            // GRUPO A: México, Sudáfrica, Corea del Sur, PO4
            // -------------------------
            [
                'match_number'        => 1,
                'tournament_group_id' => $group('A'),
                'home_team_id'        => $team('MEX'),
                'away_team_id'        => $team('RSA'),
                'match_date'          => '2026-06-11 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 2,
                'tournament_group_id' => $group('A'),
                'home_team_id'        => $team('KOR'),
                'away_team_id'        => $team('PO4'),
                'match_date'          => '2026-06-11 23:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 13,
                'tournament_group_id' => $group('A'),
                'home_team_id'        => $team('RSA'),
                'away_team_id'        => $team('PO4'),
                'match_date'          => '2026-06-18 13:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 14,
                'tournament_group_id' => $group('A'),
                'home_team_id'        => $team('MEX'),
                'away_team_id'        => $team('KOR'),
                'match_date'          => '2026-06-18 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 25,
                'tournament_group_id' => $group('A'),
                'home_team_id'        => $team('RSA'),
                'away_team_id'        => $team('KOR'),
                'match_date'          => '2026-06-24 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 26,
                'tournament_group_id' => $group('A'),
                'home_team_id'        => $team('MEX'),
                'away_team_id'        => $team('PO4'),
                'match_date'          => '2026-06-24 22:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO B: Canadá, PO1, Qatar, Suiza
            // -------------------------
            [
                'match_number'        => 3,
                'tournament_group_id' => $group('B'),
                'home_team_id'        => $team('CAN'),
                'away_team_id'        => $team('PO1'),
                'match_date'          => '2026-06-12 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 7,
                'tournament_group_id' => $group('B'),
                'home_team_id'        => $team('QAT'),
                'away_team_id'        => $team('SUI'),
                'match_date'          => '2026-06-13 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 15,
                'tournament_group_id' => $group('B'),
                'home_team_id'        => $team('SUI'),
                'away_team_id'        => $team('PO1'),
                'match_date'          => '2026-06-18 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 16,
                'tournament_group_id' => $group('B'),
                'home_team_id'        => $team('CAN'),
                'away_team_id'        => $team('QAT'),
                'match_date'          => '2026-06-18 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 27,
                'tournament_group_id' => $group('B'),
                'home_team_id'        => $team('SUI'),
                'away_team_id'        => $team('CAN'),
                'match_date'          => '2026-06-24 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 28,
                'tournament_group_id' => $group('B'),
                'home_team_id'        => $team('QAT'),
                'away_team_id'        => $team('PO1'),
                'match_date'          => '2026-06-24 16:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO C: Brasil, Marruecos, Haití, Escocia
            // -------------------------
            [
                'match_number'        => 8,
                'tournament_group_id' => $group('C'),
                'home_team_id'        => $team('BRA'),
                'away_team_id'        => $team('MAR'),
                'match_date'          => '2026-06-13 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 9,
                'tournament_group_id' => $group('C'),
                'home_team_id'        => $team('HAI'),
                'away_team_id'        => $team('SCO'),
                'match_date'          => '2026-06-13 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 19,
                'tournament_group_id' => $group('C'),
                'home_team_id'        => $team('SCO'),
                'away_team_id'        => $team('MAR'),
                'match_date'          => '2026-06-19 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 20,
                'tournament_group_id' => $group('C'),
                'home_team_id'        => $team('BRA'),
                'away_team_id'        => $team('HAI'),
                'match_date'          => '2026-06-19 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 29,
                'tournament_group_id' => $group('C'),
                'home_team_id'        => $team('MAR'),
                'away_team_id'        => $team('HAI'),
                'match_date'          => '2026-06-24 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 30,
                'tournament_group_id' => $group('C'),
                'home_team_id'        => $team('SCO'),
                'away_team_id'        => $team('BRA'),
                'match_date'          => '2026-06-24 19:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO D: Estados Unidos, Paraguay, Australia, PO3
            // -------------------------
            [
                'match_number'        => 4,
                'tournament_group_id' => $group('D'),
                'home_team_id'        => $team('USA'),
                'away_team_id'        => $team('PAR'),
                'match_date'          => '2026-06-12 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 10,
                'tournament_group_id' => $group('D'),
                'home_team_id'        => $team('AUS'),
                'away_team_id'        => $team('PO3'),
                'match_date'          => '2026-06-14 01:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 17,
                'tournament_group_id' => $group('D'),
                'home_team_id'        => $team('USA'),
                'away_team_id'        => $team('AUS'),
                'match_date'          => '2026-06-19 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 18,
                'tournament_group_id' => $group('D'),
                'home_team_id'        => $team('PAR'),
                'away_team_id'        => $team('PO3'),
                'match_date'          => '2026-06-20 01:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 31,
                'tournament_group_id' => $group('D'),
                'home_team_id'        => $team('PAR'),
                'away_team_id'        => $team('AUS'),
                'match_date'          => '2026-06-25 23:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 32,
                'tournament_group_id' => $group('D'),
                'home_team_id'        => $team('USA'),
                'away_team_id'        => $team('PO3'),
                'match_date'          => '2026-06-25 23:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO E: Alemania, Curazao, Costa de Marfil, Ecuador
            // -------------------------
            [
                'match_number'        => 11,
                'tournament_group_id' => $group('E'),
                'home_team_id'        => $team('GER'),
                'away_team_id'        => $team('CUW'),
                'match_date'          => '2026-06-14 14:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 12,
                'tournament_group_id' => $group('E'),
                'home_team_id'        => $team('CIV'),
                'away_team_id'        => $team('ECU'),
                'match_date'          => '2026-06-14 20:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 21,
                'tournament_group_id' => $group('E'),
                'home_team_id'        => $team('GER'),
                'away_team_id'        => $team('CIV'),
                'match_date'          => '2026-06-20 17:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 22,
                'tournament_group_id' => $group('E'),
                'home_team_id'        => $team('ECU'),
                'away_team_id'        => $team('CUW'),
                'match_date'          => '2026-06-20 21:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 33,
                'tournament_group_id' => $group('E'),
                'home_team_id'        => $team('CUW'),
                'away_team_id'        => $team('CIV'),
                'match_date'          => '2026-06-25 17:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 34,
                'tournament_group_id' => $group('E'),
                'home_team_id'        => $team('ECU'),
                'away_team_id'        => $team('GER'),
                'match_date'          => '2026-06-25 17:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO F: Países Bajos, Japón, PO2, Túnez
            // -------------------------
            [
                'match_number'        => 5,
                'tournament_group_id' => $group('F'),
                'home_team_id'        => $team('NED'),
                'away_team_id'        => $team('JPN'),
                'match_date'          => '2026-06-14 17:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 6,
                'tournament_group_id' => $group('F'),
                'home_team_id'        => $team('TUN'),
                'away_team_id'        => $team('PO2'),
                'match_date'          => '2026-06-14 23:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 23,
                'tournament_group_id' => $group('F'),
                'home_team_id'        => $team('NED'),
                'away_team_id'        => $team('PO2'),
                'match_date'          => '2026-06-20 14:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 24,
                'tournament_group_id' => $group('F'),
                'home_team_id'        => $team('TUN'),
                'away_team_id'        => $team('JPN'),
                'match_date'          => '2026-06-21 01:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 35,
                'tournament_group_id' => $group('F'),
                'home_team_id'        => $team('JPN'),
                'away_team_id'        => $team('PO2'),
                'match_date'          => '2026-06-25 20:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 36,
                'tournament_group_id' => $group('F'),
                'home_team_id'        => $team('TUN'),
                'away_team_id'        => $team('NED'),
                'match_date'          => '2026-06-25 20:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO G: Bélgica, Egipto, Irán, Nueva Zelanda
            // -------------------------
            [
                'match_number'        => 37,
                'tournament_group_id' => $group('G'),
                'home_team_id'        => $team('BEL'),
                'away_team_id'        => $team('EGY'),
                'match_date'          => '2026-06-15 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 38,
                'tournament_group_id' => $group('G'),
                'home_team_id'        => $team('IRN'),
                'away_team_id'        => $team('NZL'),
                'match_date'          => '2026-06-15 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 43,
                'tournament_group_id' => $group('G'),
                'home_team_id'        => $team('BEL'),
                'away_team_id'        => $team('IRN'),
                'match_date'          => '2026-06-21 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 44,
                'tournament_group_id' => $group('G'),
                'home_team_id'        => $team('NZL'),
                'away_team_id'        => $team('EGY'),
                'match_date'          => '2026-06-21 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 49,
                'tournament_group_id' => $group('G'),
                'home_team_id'        => $team('EGY'),
                'away_team_id'        => $team('IRN'),
                'match_date'          => '2026-06-26 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 50,
                'tournament_group_id' => $group('G'),
                'home_team_id'        => $team('NZL'),
                'away_team_id'        => $team('BEL'),
                'match_date'          => '2026-06-26 16:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO H: España, Cabo Verde, Arabia Saudita, Uruguay
            // -------------------------
            [
                'match_number'        => 39,
                'tournament_group_id' => $group('H'),
                'home_team_id'        => $team('ESP'),
                'away_team_id'        => $team('CPV'),
                'match_date'          => '2026-06-15 13:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 40,
                'tournament_group_id' => $group('H'),
                'home_team_id'        => $team('KSA'),
                'away_team_id'        => $team('URU'),
                'match_date'          => '2026-06-15 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 45,
                'tournament_group_id' => $group('H'),
                'home_team_id'        => $team('ESP'),
                'away_team_id'        => $team('KSA'),
                'match_date'          => '2026-06-21 13:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 46,
                'tournament_group_id' => $group('H'),
                'home_team_id'        => $team('URU'),
                'away_team_id'        => $team('CPV'),
                'match_date'          => '2026-06-21 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 51,
                'tournament_group_id' => $group('H'),
                'home_team_id'        => $team('KSA'),
                'away_team_id'        => $team('CPV'),
                'match_date'          => '2026-06-26 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 52,
                'tournament_group_id' => $group('H'),
                'home_team_id'        => $team('URU'),
                'away_team_id'        => $team('ESP'),
                'match_date'          => '2026-06-26 19:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO I: Francia, Senegal, RF2, Noruega
            // -------------------------
            [
                'match_number'        => 41,
                'tournament_group_id' => $group('I'),
                'home_team_id'        => $team('FRA'),
                'away_team_id'        => $team('SEN'),
                'match_date'          => '2026-06-16 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 42,
                'tournament_group_id' => $group('I'),
                'home_team_id'        => $team('RF2'),
                'away_team_id'        => $team('NOR'),
                'match_date'          => '2026-06-16 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 47,
                'tournament_group_id' => $group('I'),
                'home_team_id'        => $team('FRA'),
                'away_team_id'        => $team('RF2'),
                'match_date'          => '2026-06-22 18:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 48,
                'tournament_group_id' => $group('I'),
                'home_team_id'        => $team('NOR'),
                'away_team_id'        => $team('SEN'),
                'match_date'          => '2026-06-22 21:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 53,
                'tournament_group_id' => $group('I'),
                'home_team_id'        => $team('NOR'),
                'away_team_id'        => $team('FRA'),
                'match_date'          => '2026-06-26 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 54,
                'tournament_group_id' => $group('I'),
                'home_team_id'        => $team('SEN'),
                'away_team_id'        => $team('RF2'),
                'match_date'          => '2026-06-26 16:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO J: Argentina, Argelia, Austria, Jordania
            // -------------------------
            [
                'match_number'        => 55,
                'tournament_group_id' => $group('J'),
                'home_team_id'        => $team('ARG'),
                'away_team_id'        => $team('ALG'),
                'match_date'          => '2026-06-16 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 56,
                'tournament_group_id' => $group('J'),
                'home_team_id'        => $team('AUT'),
                'away_team_id'        => $team('JOR'),
                'match_date'          => '2026-06-17 01:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 61,
                'tournament_group_id' => $group('J'),
                'home_team_id'        => $team('ARG'),
                'away_team_id'        => $team('AUT'),
                'match_date'          => '2026-06-22 14:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 62,
                'tournament_group_id' => $group('J'),
                'home_team_id'        => $team('JOR'),
                'away_team_id'        => $team('ALG'),
                'match_date'          => '2026-06-23 00:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 67,
                'tournament_group_id' => $group('J'),
                'home_team_id'        => $team('ALG'),
                'away_team_id'        => $team('AUT'),
                'match_date'          => '2026-06-27 16:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 68,
                'tournament_group_id' => $group('J'),
                'home_team_id'        => $team('JOR'),
                'away_team_id'        => $team('ARG'),
                'match_date'          => '2026-06-27 16:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO K: Portugal, RF1, Uzbekistán, Colombia
            // -------------------------
            [
                'match_number'        => 57,
                'tournament_group_id' => $group('K'),
                'home_team_id'        => $team('POR'),
                'away_team_id'        => $team('RF1'),
                'match_date'          => '2026-06-17 14:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 58,
                'tournament_group_id' => $group('K'),
                'home_team_id'        => $team('UZB'),
                'away_team_id'        => $team('COL'),
                'match_date'          => '2026-06-17 23:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 63,
                'tournament_group_id' => $group('K'),
                'home_team_id'        => $team('POR'),
                'away_team_id'        => $team('UZB'),
                'match_date'          => '2026-06-23 14:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 64,
                'tournament_group_id' => $group('K'),
                'home_team_id'        => $team('COL'),
                'away_team_id'        => $team('RF1'),
                'match_date'          => '2026-06-23 23:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 69,
                'tournament_group_id' => $group('K'),
                'home_team_id'        => $team('RF1'),
                'away_team_id'        => $team('UZB'),
                'match_date'          => '2026-06-27 19:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 70,
                'tournament_group_id' => $group('K'),
                'home_team_id'        => $team('COL'),
                'away_team_id'        => $team('POR'),
                'match_date'          => '2026-06-27 19:00:00',
                'stage'               => 'fase_grupos',
            ],

            // -------------------------
            // GRUPO L: Inglaterra, Croacia, Ghana, Panamá
            // -------------------------
            [
                'match_number'        => 59,
                'tournament_group_id' => $group('L'),
                'home_team_id'        => $team('ENG'),
                'away_team_id'        => $team('CRO'),
                'match_date'          => '2026-06-17 17:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 60,
                'tournament_group_id' => $group('L'),
                'home_team_id'        => $team('GHA'),
                'away_team_id'        => $team('PAN'),
                'match_date'          => '2026-06-17 20:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 65,
                'tournament_group_id' => $group('L'),
                'home_team_id'        => $team('ENG'),
                'away_team_id'        => $team('GHA'),
                'match_date'          => '2026-06-23 17:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 66,
                'tournament_group_id' => $group('L'),
                'home_team_id'        => $team('PAN'),
                'away_team_id'        => $team('CRO'),
                'match_date'          => '2026-06-23 20:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 71,
                'tournament_group_id' => $group('L'),
                'home_team_id'        => $team('CRO'),
                'away_team_id'        => $team('GHA'),
                'match_date'          => '2026-06-27 22:00:00',
                'stage'               => 'fase_grupos',
            ],
            [
                'match_number'        => 72,
                'tournament_group_id' => $group('L'),
                'home_team_id'        => $team('PAN'),
                'away_team_id'        => $team('ENG'),
                'match_date'          => '2026-06-27 22:00:00',
                'stage'               => 'fase_grupos',
            ],
        ];

        foreach ($matches as $match) {
            Matche::create(array_merge($match, ['status' => 'pendiente']));
        }
    }
}
