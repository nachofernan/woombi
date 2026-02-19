<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matche extends Model
{
    protected $table = 'matches'; // evita conflicto con palabra reservada

    protected $fillable = [
        'tournament_group_id', 'home_team_id', 'away_team_id',
        'home_source_match_id', 'away_source_match_id',
        'home_source_result', 'away_source_result',
        'stage', 'match_number', 'match_date',
        'home_score', 'away_score', 'home_extra_score', 'away_extra_score',
        'penalty_winner_id', 'status'
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'home_score' => 'integer',
        'away_score' => 'integer',
        'home_extra_score' => 'integer',
        'away_extra_score' => 'integer',
    ];

    public function tournamentGroup() { return $this->belongsTo(TournamentGroup::class); }
    public function homeTeam() { return $this->belongsTo(Team::class, 'home_team_id'); }
    public function awayTeam() { return $this->belongsTo(Team::class, 'away_team_id'); }
    public function penaltyWinner() { return $this->belongsTo(Team::class, 'penalty_winner_id'); }
    public function homeSourceMatch() { return $this->belongsTo(Matche::class, 'home_source_match_id'); }
    public function awaySourceMatch() { return $this->belongsTo(Matche::class, 'away_source_match_id'); }
    public function predictions() { return $this->hasMany(Prediction::class); }

    public function getWinner(): ?Team
    {
        if ($this->penalty_winner_id) return $this->penaltyWinner;
        if ($this->home_score > $this->away_score) return $this->homeTeam;
        if ($this->away_score > $this->home_score) return $this->awayTeam;
        return null; // empate en fase de grupos
    }
}
