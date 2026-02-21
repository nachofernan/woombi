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
            ['name' => 'México',            'fifa_code' => 'MEX',  'flag_url' => 'mx'],
            ['name' => 'Sudáfrica',         'fifa_code' => 'RSA',  'flag_url' => 'za'],
            ['name' => 'Corea del Sur',     'fifa_code' => 'KOR',  'flag_url' => 'kr'],
            ['name' => 'Repechaje UEFA D',  'fifa_code' => 'PO4',  'flag_url' => null], // Dinamarca/Chequia/Macedonia/Irlanda

            // Grupo B
            ['name' => 'Canadá',            'fifa_code' => 'CAN',  'flag_url' => 'ca'],
            ['name' => 'Repechaje UEFA A',  'fifa_code' => 'PO1',  'flag_url' => null], // Italia/Irlanda del Norte/Gales/Bosnia
            ['name' => 'Qatar',             'fifa_code' => 'QAT',  'flag_url' => 'qa'],
            ['name' => 'Suiza',             'fifa_code' => 'SUI',  'flag_url' => 'ch'],

            // Grupo C
            ['name' => 'Brasil',            'fifa_code' => 'BRA',  'flag_url' => 'br'],
            ['name' => 'Marruecos',         'fifa_code' => 'MAR',  'flag_url' => 'ma'],
            ['name' => 'Escocia',           'fifa_code' => 'SCO',  'flag_url' => 'gb-sct'],
            ['name' => 'Haití',             'fifa_code' => 'HAI',  'flag_url' => 'ht'],

            // Grupo D
            ['name' => 'Estados Unidos',    'fifa_code' => 'USA',  'flag_url' => 'us'],
            ['name' => 'Paraguay',          'fifa_code' => 'PAR',  'flag_url' => 'py'],
            ['name' => 'Australia',         'fifa_code' => 'AUS',  'flag_url' => 'au'],
            ['name' => 'Repechaje UEFA C',  'fifa_code' => 'PO3',  'flag_url' => null], // Turquía/Rumanía/Eslovaquia/Kosovo

            // Grupo E
            ['name' => 'Alemania',          'fifa_code' => 'GER',  'flag_url' => 'de'],
            ['name' => 'Curazao',           'fifa_code' => 'CUW',  'flag_url' => 'cw'],
            ['name' => 'Costa de Marfil',   'fifa_code' => 'CIV',  'flag_url' => 'ci'],
            ['name' => 'Ecuador',           'fifa_code' => 'ECU',  'flag_url' => 'ec'],

            // Grupo F
            ['name' => 'Países Bajos',      'fifa_code' => 'NED',  'flag_url' => 'nl'],
            ['name' => 'Japón',             'fifa_code' => 'JPN',  'flag_url' => 'jp'],
            ['name' => 'Repechaje UEFA B',  'fifa_code' => 'PO2',  'flag_url' => null], // Ucrania/Suecia/Polonia/Albania
            ['name' => 'Túnez',             'fifa_code' => 'TUN',  'flag_url' => 'tn'],

            // Grupo G
            ['name' => 'Bélgica',           'fifa_code' => 'BEL',  'flag_url' => 'be'],
            ['name' => 'Egipto',            'fifa_code' => 'EGY',  'flag_url' => 'eg'],
            ['name' => 'Irán',              'fifa_code' => 'IRN',  'flag_url' => 'ir'],
            ['name' => 'Nueva Zelanda',     'fifa_code' => 'NZL',  'flag_url' => 'nz'],

            // Grupo H
            ['name' => 'España',            'fifa_code' => 'ESP',  'flag_url' => 'es'],
            ['name' => 'Cabo Verde',        'fifa_code' => 'CPV',  'flag_url' => 'cv'],
            ['name' => 'Arabia Saudita',    'fifa_code' => 'KSA',  'flag_url' => 'sa'],
            ['name' => 'Uruguay',           'fifa_code' => 'URU',  'flag_url' => 'uy'],

            // Grupo I
            ['name' => 'Francia',           'fifa_code' => 'FRA',  'flag_url' => 'fr'],
            ['name' => 'Noruega',           'fifa_code' => 'NOR',  'flag_url' => 'no'],
            ['name' => 'Senegal',           'fifa_code' => 'SEN',  'flag_url' => 'sn'],
            ['name' => 'Repechaje FIFA 2',  'fifa_code' => 'RF2',  'flag_url' => null], // Bolivia/Surinam/Irak

            // Grupo J
            ['name' => 'Argentina',         'fifa_code' => 'ARG',  'flag_url' => 'ar'],
            ['name' => 'Argelia',           'fifa_code' => 'ALG',  'flag_url' => 'dz'],
            ['name' => 'Austria',           'fifa_code' => 'AUT',  'flag_url' => 'at'],
            ['name' => 'Jordania',          'fifa_code' => 'JOR',  'flag_url' => 'jo'],

            // Grupo K
            ['name' => 'Portugal',          'fifa_code' => 'POR',  'flag_url' => 'pt'],
            ['name' => 'Repechaje FIFA 1',  'fifa_code' => 'RF1',  'flag_url' => null], // RD Congo/Jamaica/Nueva Caledonia
            ['name' => 'Uzbekistán',        'fifa_code' => 'UZB',  'flag_url' => 'uz'],
            ['name' => 'Colombia',          'fifa_code' => 'COL',  'flag_url' => 'co'],

            // Grupo L
            ['name' => 'Inglaterra',        'fifa_code' => 'ENG',  'flag_url' => 'gb-eng'],
            ['name' => 'Croacia',           'fifa_code' => 'CRO',  'flag_url' => 'hr'],
            ['name' => 'Ghana',             'fifa_code' => 'GHA',  'flag_url' => 'gh'],
            ['name' => 'Panamá',            'fifa_code' => 'PAN',  'flag_url' => 'pa'],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}