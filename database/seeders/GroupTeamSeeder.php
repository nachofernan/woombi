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
            'A' => ['MEX', 'RSA', 'KOR', 'PO4'],
            'B' => ['CAN', 'PO1', 'QAT', 'SUI'],
            'C' => ['BRA', 'MAR', 'HAI', 'SCO'],
            'D' => ['USA', 'PAR', 'AUS', 'PO3'],
            'E' => ['GER', 'CUW', 'CIV', 'ECU'],
            'F' => ['NED', 'JPN', 'PO2', 'TUN'],
            'G' => ['BEL', 'EGY', 'IRN', 'NZL'],
            'H' => ['ESP', 'CPV', 'KSA', 'URU'],
            'I' => ['FRA', 'NOR', 'SEN', 'RF2'],
            'J' => ['ARG', 'ALG', 'AUT', 'JOR'],
            'K' => ['POR', 'RF1', 'UZB', 'COL'],
            'L' => ['ENG', 'CRO', 'GHA', 'PAN'],
        ];

        foreach ($assignments as $groupName => $codes) {
            $group = TournamentGroup::where('name', $groupName)->first();
            $teamIds = Team::whereIn('fifa_code', $codes)->pluck('id');
            $group->teams()->attach($teamIds);
        }
    }
}