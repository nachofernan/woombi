<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentGroup extends Model
{
    //
    protected $fillable = ['name'];

    public function teams() { return $this->belongsToMany(Team::class, 'group_team'); }
    public function matches() { return $this->hasMany(Matche::class); }
}
