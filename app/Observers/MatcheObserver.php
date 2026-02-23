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
            $sourceField = "{$side}_source_match_id";
            $resultField = "{$side}_source_result";
            $teamField   = "{$side}_team_id";

            $nexts = Matche::where($sourceField, $match->id)->get();

            foreach ($nexts as $next) {
                $resolved = $next->$resultField === 'ganador' ? $winner : $loser;
                if ($resolved) $next->update([$teamField => $resolved->id]);
            }
        }
    }

    private function calculatePredictions(Matche $match): void
    {
        $match1   = \App\Models\Matche::find(config('prode.match_kickoff'));
        $match73  = \App\Models\Matche::find(config('prode.match_16avos'));
        $match101 = \App\Models\Matche::find(config('prode.match_semis'));

        $scorePoints  = match(true) {
            $match101 && $match->id >= $match101->id => config('prode.score_points.final'),
            $match73  && $match->id >= $match73->id  => config('prode.score_points.medio'),
            default                                   => config('prode.score_points.grupos'),
        };
        $resultPoints = match(true) {
            $match101 && $match->id >= $match101->id => config('prode.result_points.final'),
            $match73  && $match->id >= $match73->id  => config('prode.result_points.medio'),
            default                                   => config('prode.result_points.grupos'),
        };

        $actualResult = match(true) {
            $match->home_score > $match->away_score => 'home',
            $match->away_score > $match->home_score => 'away',
            default                                  => 'draw',
        };

        $winner = $match->getWinner(); // considera penales

        foreach ($match->predictions as $prediction) {
            $pts = 0;

            // Scores individuales
            if ($prediction->predicted_home_score === $match->home_score) $pts += $scorePoints;
            if ($prediction->predicted_away_score === $match->away_score) $pts += $scorePoints;

            // Resultado (dirección)
            $predictedResult = match(true) {
                $prediction->predicted_home_score > $prediction->predicted_away_score => 'home',
                $prediction->predicted_away_score > $prediction->predicted_home_score => 'away',
                default                                                                 => 'draw',
            };
            $resultadoAcertado = $predictedResult === $actualResult;
            if ($resultadoAcertado) $pts += $resultPoints;

            // Bonus +2
            $isPredictedDraw = $predictedResult === 'draw';
            $quienPasaAcertado = $match->stage !== 'fase_grupos'
                && $winner
                && (
                    (!$isPredictedDraw && $predictedResult === ($actualResult))
                    || ($isPredictedDraw && $prediction->predicted_winner_team_id === $winner->id)
                );

            if (
                // En caso de querer que los grupos también tengan bonus: descomenta la siguiente línea
                // ($match->stage === 'fase_grupos' && $resultadoAcertado) ||
                $quienPasaAcertado
            ) {
                $pts += config('prode.bonus_points');
            }

            $prediction->update(['points' => $pts]);
            $this->updatePoints($prediction);
        }

        if ($match->stage === 'final') {
            $this->calculateChampion($match);
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
    }

    private function calculateChampion(Matche $match): void
    {
        $champion = $match->getWinner();
        if (!$champion) return;

        $match1   = \App\Models\Matche::find(config('prode.match_kickoff'));
        $match73  = \App\Models\Matche::find(config('prode.match_16avos'));
        $match101 = \App\Models\Matche::find(config('prode.match_semis'));

        $winners = \App\Models\User::where('champion_team_id', $champion->id)->get();

        foreach ($winners as $user) {
            $updatedAt = $user->champion_updated_at;

            $bonus = match(true) {
                $match1  && $updatedAt < $match1->match_date  => config('prode.champion_bonus.antes_mundial'),
                $match73 && $updatedAt < $match73->match_date => config('prode.champion_bonus.medio'),
                default                                        => config('prode.champion_bonus.final'),
            };

            $total = $user->predictions()->sum('points') + $bonus;
            $user->update(['total_points' => $total]);
        }
    }
}