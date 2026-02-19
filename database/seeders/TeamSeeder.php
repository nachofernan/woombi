<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            // Grupo A
            ['name' => 'México',            'fifa_code' => 'MEX'],
            ['name' => 'Sudáfrica',         'fifa_code' => 'RSA'],
            ['name' => 'Corea del Sur',     'fifa_code' => 'KOR'],
            ['name' => 'Repechaje UEFA D',  'fifa_code' => 'PO4'], // Dinamarca/Chequia/Macedonia/Irlanda

            // Grupo B
            ['name' => 'Canadá',            'fifa_code' => 'CAN'],
            ['name' => 'Repechaje UEFA A',  'fifa_code' => 'PO1'], // Italia/Irlanda del Norte/Gales/Bosnia
            ['name' => 'Qatar',             'fifa_code' => 'QAT'],
            ['name' => 'Suiza',             'fifa_code' => 'SUI'],

            // Grupo C
            ['name' => 'Brasil',            'fifa_code' => 'BRA'],
            ['name' => 'Marruecos',         'fifa_code' => 'MAR'],
            ['name' => 'Escocia',           'fifa_code' => 'SCO'],
            ['name' => 'Haití',             'fifa_code' => 'HAI'],

            // Grupo D
            ['name' => 'Estados Unidos',    'fifa_code' => 'USA'],
            ['name' => 'Paraguay',          'fifa_code' => 'PAR'],
            ['name' => 'Australia',         'fifa_code' => 'AUS'],
            ['name' => 'Repechaje UEFA C',  'fifa_code' => 'PO3'], // Turquía/Rumanía/Eslovaquia/Kosovo

            // Grupo E
            ['name' => 'Alemania',          'fifa_code' => 'GER'],
            ['name' => 'Curazao',           'fifa_code' => 'CUW'],
            ['name' => 'Costa de Marfil',   'fifa_code' => 'CIV'],
            ['name' => 'Ecuador',           'fifa_code' => 'ECU'],

            // Grupo F
            ['name' => 'Países Bajos',      'fifa_code' => 'NED'],
            ['name' => 'Japón',             'fifa_code' => 'JPN'],
            ['name' => 'Repechaje UEFA B',  'fifa_code' => 'PO2'], // Ucrania/Suecia/Polonia/Albania
            ['name' => 'Túnez',             'fifa_code' => 'TUN'],

            // Grupo G
            ['name' => 'Bélgica',           'fifa_code' => 'BEL'],
            ['name' => 'Egipto',            'fifa_code' => 'EGY'],
            ['name' => 'Irán',              'fifa_code' => 'IRN'],
            ['name' => 'Nueva Zelanda',     'fifa_code' => 'NZL'],

            // Grupo H
            ['name' => 'España',            'fifa_code' => 'ESP'],
            ['name' => 'Cabo Verde',        'fifa_code' => 'CPV'],
            ['name' => 'Arabia Saudita',    'fifa_code' => 'KSA'],
            ['name' => 'Uruguay',           'fifa_code' => 'URU'],

            // Grupo I
            ['name' => 'Francia',           'fifa_code' => 'FRA'],
            ['name' => 'Noruega',           'fifa_code' => 'NOR'],
            ['name' => 'Senegal',           'fifa_code' => 'SEN'],
            ['name' => 'Repechaje FIFA 2',  'fifa_code' => 'RF2'], // Bolivia/Surinam/Irak

            // Grupo J
            ['name' => 'Argentina',         'fifa_code' => 'ARG'],
            ['name' => 'Argelia',           'fifa_code' => 'ALG'],
            ['name' => 'Austria',           'fifa_code' => 'AUT'],
            ['name' => 'Jordania',          'fifa_code' => 'JOR'],

            // Grupo K
            ['name' => 'Portugal',          'fifa_code' => 'POR'],
            ['name' => 'Repechaje FIFA 1',  'fifa_code' => 'RF1'], // RD Congo/Jamaica/Nueva Caledonia
            ['name' => 'Uzbekistán',        'fifa_code' => 'UZB'],
            ['name' => 'Colombia',          'fifa_code' => 'COL'],

            // Grupo L
            ['name' => 'Inglaterra',        'fifa_code' => 'ENG'],
            ['name' => 'Croacia',           'fifa_code' => 'CRO'],
            ['name' => 'Ghana',             'fifa_code' => 'GHA'],
            ['name' => 'Panamá',            'fifa_code' => 'PAN'],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}