<?php

namespace App\Observers;

use App\Models\Matche;
use App\Models\Prediction;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $matches = Matche::all();
        foreach ($matches as $match) {
            Prediction::create([
                'user_id'              => $user->id,
                'match_id'             => $match->id,
                'predicted_home_score' => null,
                'predicted_away_score' => null,
                'points'               => null,
            ]);
        }
    }
}
