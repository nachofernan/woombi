<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TournamentGroup;

class TournamentGroupSeeder extends Seeder
{
    public function run(): void
    {
        $groups = ['A','B','C','D','E','F','G','H','I','J','K','L'];

        foreach ($groups as $name) {
            TournamentGroup::create(['name' => $name]);
        }
    }
}