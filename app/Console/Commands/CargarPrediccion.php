<?php

namespace App\Console\Commands;

use App\Models\Matche;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\Console\Command;

class CargarPrediccion extends Command
{
    protected $signature = 'prode:cargar-prediccion
        {partido : ID del partido}
        {participante : ID del usuario}
        {goles_local : Goles predichos del equipo local}
        {goles_visitante : Goles predichos del equipo visitante}
        {--ganador= : ID del equipo ganador en caso de empate predicho (eliminatorias)}';

    protected $description = 'Carga o actualiza a mano la predicción de un participante para un partido, saltando el bloqueo de match_date (uso: partido por arrancar)';

    public function handle(): int
    {
        $match = Matche::with('homeTeam', 'awayTeam')->find($this->argument('partido'));
        if (!$match) {
            $this->error('No existe un partido con ese ID.');
            return self::FAILURE;
        }

        $user = User::find($this->argument('participante'));
        if (!$user) {
            $this->error('No existe un participante con ese ID.');
            return self::FAILURE;
        }

        $golesLocal = (int) $this->argument('goles_local');
        $golesVisitante = (int) $this->argument('goles_visitante');

        if ($golesLocal < 0 || $golesLocal > 20 || $golesVisitante < 0 || $golesVisitante > 20) {
            $this->error('Los goles deben estar entre 0 y 20.');
            return self::FAILURE;
        }

        $ganadorId = $this->option('ganador') !== null ? (int) $this->option('ganador') : null;
        if ($ganadorId !== null && !in_array($ganadorId, [$match->home_team_id, $match->away_team_id], true)) {
            $this->error('El --ganador debe ser el ID de uno de los dos equipos del partido.');
            return self::FAILURE;
        }

        $homeName = $match->homeTeam->name ?? "Equipo local #{$match->home_team_id}";
        $awayName = $match->awayTeam->name ?? "Equipo visitante #{$match->away_team_id}";

        $existente = Prediction::where('user_id', $user->id)->where('match_id', $match->id)->first();

        $this->newLine();
        $this->line('<fg=yellow;options=bold>Se va a cargar la siguiente predicción (bypass de bloqueo de horario):</>');
        $this->line("  Partido:      #{$match->id} (match_number {$match->match_number}, {$match->stage})");
        $this->line("  Fecha:        {$match->match_date}" . ($match->match_date <= now() ? ' <fg=red>(ya comenzó)</>' : ''));
        $this->line("  Participante: {$user->name} (ID {$user->id})");
        $this->line("  Predicción:   {$homeName} {$golesLocal} - {$golesVisitante} {$awayName}");
        if ($ganadorId !== null) {
            $ganadorName = $ganadorId === $match->home_team_id ? $homeName : $awayName;
            $this->line("  Ganador (si empate a penales): {$ganadorName}");
        }
        if ($existente) {
            $this->line("  <fg=gray>(reemplaza predicción existente: {$existente->predicted_home_score} - {$existente->predicted_away_score})</>");
        }
        $this->newLine();

        if (!$this->confirm('¿Confirmás la carga?', true)) {
            $this->info('Cancelado.');
            return self::SUCCESS;
        }

        $prediction = Prediction::updateOrCreate(
            ['user_id' => $user->id, 'match_id' => $match->id],
            [
                'predicted_home_score' => $golesLocal,
                'predicted_away_score' => $golesVisitante,
                'predicted_winner_team_id' => $ganadorId,
            ]
        );

        $this->info("Predicción #{$prediction->id} cargada correctamente.");
        return self::SUCCESS;
    }
}
