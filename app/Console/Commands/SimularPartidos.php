<?php

namespace App\Console\Commands;

use App\Models\Matche;
use Illuminate\Console\Command;

class SimularPartidos extends Command
{
    protected $signature   = 'prode:simular';
    protected $description = 'Simula resultados random para partidos pendientes';

    public function handle(): void
    {
        $siguiente = Matche::where('status', '!=', 'finalizado')
            ->whereNotNull('home_team_id')
            ->whereNotNull('away_team_id')
            ->orderBy('id')
            ->first();

        if (!$siguiente) {
            $this->error('No hay partidos pendientes con equipos asignados.');
            return;
        }

        $this->info("Siguiente partido disponible: #{$siguiente->id} — {$siguiente->homeTeam->name} vs {$siguiente->awayTeam->name} (match_number: {$siguiente->match_number})");

        $cantidad = (int) $this->ask('¿Cuántos partidos querés simular?');

        if ($cantidad <= 0) {
            $this->error('La cantidad debe ser mayor a 0.');
            return;
        }

        $partidos = Matche::where('status', '!=', 'finalizado')
            ->whereNotNull('home_team_id')
            ->whereNotNull('away_team_id')
            ->where('id', '>=', $siguiente->id)
            ->orderBy('id')
            ->limit($cantidad)
            ->get();

        if (!$this->confirm("Se van a simular {$partidos->count()} partido(s). ¿Confirmás?")) {
            $this->info('Cancelado.');
            return;
        }

        foreach ($partidos as $partido) {
            $homeScore = rand(0, 4);
            $awayScore = rand(0, 4);
            $penaltyWinnerId = null;

            if ($partido->stage !== 'fase_grupos' && $homeScore === $awayScore) {
                $penaltyWinnerId = collect([$partido->home_team_id, $partido->away_team_id])->random();
            }

            $partido->update([
                'home_score'        => $homeScore,
                'away_score'        => $awayScore,
                'penalty_winner_id' => $penaltyWinnerId,
                'status'            => 'finalizado',
            ]);

            $label = $penaltyWinnerId
                ? "(pen. " . ($penaltyWinnerId === $partido->home_team_id ? $partido->homeTeam->name : $partido->awayTeam->name) . ")"
                : '';

            $this->line("✓ #{$partido->id} {$partido->homeTeam->name} {$homeScore}-{$awayScore} {$partido->awayTeam->name} {$label}");
        }

        $this->info('Simulación completada.');
    }
}