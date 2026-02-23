<?php

namespace App\Console\Commands;

use App\Models\Matche;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class SimularPartidos extends Command
{
    protected $signature = 'prode:simular {--reset : Resetea todos los partidos al estado inicial}';
    protected $description = 'Simula resultados random para partidos pendientes';

    public function handle(): void
    {
        if ($this->option('reset')) {
            if (!$this->confirm('¿Resetear todos los partidos al estado inicial?')) {
                $this->info('Cancelado.');
                return;
            }

            Matche::where('stage', 'fase_grupos')->update([
                'home_score'        => null,
                'away_score'        => null,
                'penalty_winner_id' => null,
                'status'            => 'pendiente',
            ]);

            Matche::where('stage', '!=', 'fase_grupos')->update([
                'home_team_id'      => null,
                'away_team_id'      => null,
                'home_score'        => null,
                'away_score'        => null,
                'penalty_winner_id' => null,
                'status'            => 'pendiente',
            ]);

            // Resetear puntos de usuarios y predicciones
            \App\Models\User::query()->update(['total_points' => 0]);
            \App\Models\Prediction::query()->update(['points' => null]);

            $this->info('Partidos reseteados.');
            return;
        }

        $siguiente = Matche::where('status', '!=', 'finalizado')
            ->orderBy('id')
            ->first();

        if (!$siguiente) {
            $this->error('No hay partidos pendientes con equipos asignados.');
            return;
        }

        $this->info("Siguiente partido disponible: #{$siguiente->id} (match_number: {$siguiente->match_number})");

        $cantidad = (int) $this->ask('¿Cuántos partidos querés simular?', 150);

        if ($cantidad <= 0) {
            $this->error('La cantidad debe ser mayor a 0.');
            return;
        }

        $partidos = Matche::where('status', '!=', 'finalizado')
            ->where('id', '>=', $siguiente->id)
            ->orderBy('id')
            ->limit($cantidad)
            ->get();

        if (!$this->confirm("Se van a simular {$partidos->count()} partido(s). ¿Confirmás?", true)) {
            $this->info('Cancelado.');
            return;
        }

        foreach ($partidos as $partido) {
            $partido->refresh();
            if ($partido->stage === 'dieciseisavos') {
                $teams = \App\Models\Team::pluck('id');
                if (!$partido->home_team_id) {
                    $partido->update(['home_team_id' => $teams->random()]);
                }
                if (!$partido->away_team_id) {
                    $away = $teams->reject(fn($id) => $id === $partido->home_team_id)->random();
                    $partido->update(['away_team_id' => $away]);
                }
                $partido->refresh();
            }
            if (!$partido->home_team_id || !$partido->away_team_id) {
                $this->warn("Partido #{$partido->id} (match {$partido->match_number}) sin equipos asignados, saltando.");
                $this->warn("{$partido->home_team_id} vs {$partido->away_team_id} (match {$partido->match_number}) sin equipos asignados, saltando.");
                continue;
            }

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