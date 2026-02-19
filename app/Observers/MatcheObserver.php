<?php

namespace App\Observers;

use App\Models\Matche;
use App\Models\Prediction;

class MatcheObserver
{
    public function updated(Matche $match): void
    {
        if ($match->status !== 'finalizado') return;
        if (!$match->wasChanged('status')) return;

        $this->resolveNextSlot($match);
        $this->calculatePredictions($match);
    }

    private function resolveNextSlot(Matche $match): void
    {
        $winner = $match->getWinner();
        $loser  = ($winner?->id === $match->home_team_id) ? $match->awayTeam : $match->homeTeam;

        foreach (['home', 'away'] as $side) {
            $sourceField  = "{$side}_source_match_id";
            $resultField  = "{$side}_source_result";
            $teamField    = "{$side}_team_id";

            $next = Matche::where($sourceField, $match->id)->first();
            if (!$next) continue;

            $resolved = $next->$resultField === 'ganador' ? $winner : $loser;
            if ($resolved) $next->update([$teamField => $resolved->id]);
        }
    }

    private function calculatePredictions(Matche $match): void
    {
        $winner = $match->getWinner();

        foreach ($match->predictions as $prediction) {
            $points = 0;
            $exactHome = $prediction->predicted_home_score === $match->home_score;
            $exactAway = $prediction->predicted_away_score === $match->away_score;

            if ($exactHome && $exactAway) {
                $points = 3; // resultado exacto
            } elseif ($winner && $this->predictedWinner($prediction, $match) === $winner->id) {
                $points = 1; // acertó ganador
            }

            $prediction->update(['points' => $points]);
            $this->updatePoints($prediction);
        }
    }

    private function predictedWinner(object $prediction, Matche $match): ?int
    {
        if ($prediction->predicted_home_score > $prediction->predicted_away_score) return $match->home_team_id;
        if ($prediction->predicted_away_score > $prediction->predicted_home_score) return $match->away_team_id;
        return null;
    }

    private function updatePoints(Prediction $prediction): void
    {
        $user = $prediction->user;
        $total = $user->predictions()->sum('points');
        
        $user->update(['total_points' => $total]);
        
        $user->groups()->each(fn($group) => 
            $group->users()->updateExistingPivot($user->id, ['total_points' => $total])
        );
    }
}