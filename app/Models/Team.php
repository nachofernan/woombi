<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    protected $fillable = ['name', 'flag_url', 'fifa_code'];

    public function tournamentGroups() { return $this->belongsToMany(TournamentGroup::class, 'group_team'); }
    public function homeMatches() { return $this->hasMany(Matche::class, 'home_team_id'); }
    public function awayMatches() { return $this->hasMany(Matche::class, 'away_team_id'); }
}
