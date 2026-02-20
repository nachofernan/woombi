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
            $predictedDraw = $prediction->predicted_home_score === $prediction->predicted_away_score;

            if ($exactHome && $exactAway) {
                // En eliminatorias con empate en 90', también tiene que acertar el ganador
                if ($predictedDraw && $match->stage !== 'fase_grupos') {
                    $points = ($prediction->predicted_winner_team_id === $winner?->id) ? 3 : 0;
                } else {
                    $points = 3;
                }
            } elseif ($winner) {
                $predictedWinner = $this->predictedWinner($prediction, $match);
                // En eliminatorias, si predijo empate usa predicted_winner_team_id
                if ($predictedDraw && $match->stage !== 'fase_grupos') {
                    $predictedWinner = $prediction->predicted_winner_team_id;
                }
                if ($predictedWinner === $winner->id) {
                    $points = 1;
                }
            }

            $prediction->update(['points' => $points]);
            $this->updatePoints($prediction);
            if ($match->stage === 'final') {
                $this->calculateChampion($match);
            }
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

    private function calculateChampion(Matche $match): void
    {
        $champion = $match->getWinner();
        if (!$champion) return;

        $winners = \App\Models\User::where('champion_team_id', $champion->id)->get();

        foreach ($winners as $user) {
            $total = $user->predictions()->sum('points') + 50;
            $user->update(['total_points' => $total]);

            $user->groups()->each(fn($group) =>
                $group->users()->updateExistingPivot($user->id, ['total_points' => $total])
            );
        }
    }
}