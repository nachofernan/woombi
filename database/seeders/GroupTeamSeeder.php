<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\TournamentGroup;

class GroupTeamSeeder extends Seeder
{
    public function run(): void
    {
        $assignments = [
            'A' => ['MEX', 'RSA', 'KOR', 'CZE'],
            'B' => ['CAN', 'BIH', 'QAT', 'SUI'],
            'C' => ['BRA', 'MAR', 'HAI', 'SCO'],
            'D' => ['USA', 'PAR', 'AUS', 'TUR'],
            'E' => ['GER', 'CUW', 'CIV', 'ECU'],
            'F' => ['NED', 'JPN', 'SWE', 'TUN'],
            'G' => ['BEL', 'EGY', 'IRN', 'NZL'],
            'H' => ['ESP', 'CPV', 'KSA', 'URU'],
            'I' => ['FRA', 'NOR', 'SEN', 'IRQ'],
            'J' => ['ARG', 'ALG', 'AUT', 'JOR'],
            'K' => ['POR', 'COD', 'UZB', 'COL'],
            'L' => ['ENG', 'CRO', 'GHA', 'PAN'],
        ];

        foreach ($assignments as $groupName => $codes) {
            $group = TournamentGroup::where('name', $groupName)->first();
            $teamIds = Team::whereIn('fifa_code', $codes)->pluck('id');
            $group->teams()->attach($teamIds);
        }
    }
}