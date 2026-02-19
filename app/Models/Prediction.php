<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $fillable = ['user_id', 'match_id', 'predicted_home_score', 'predicted_away_score', 'points'];

    protected $casts = [
        'predicted_home_score' => 'integer',
        'predicted_away_score' => 'integer',
        'points' => 'integer',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function match() { return $this->belongsTo(Matche::class); }
}
