<?php

namespace App\Console\Commands;

use App\Models\Matche;
use Illuminate\Console\Command;

class ReordenarCruces extends Command
{
    protected $signature = 'prode:reordenar-cruces {--dry-run : Muestra los cambios sin aplicarlos}';
    protected $description = 'Reordena los source_match_id de los cruces eliminatorios según la nueva estructura';

    // [match_number => [home_source_match_number, away_source_match_number]]
    private array $cruces = [
        89  => [74, 77],
        90  => [73, 75],
        91  => [76, 78],
        93  => [83, 84],
        94  => [81, 82],
        95  => [86, 88],
        96  => [85, 87],
        98  => [93, 94],
        99  => [91, 92],
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('--- DRY RUN: no se guardará nada ---');
        }

        $errors = 0;

        foreach ($this->cruces as $matchNumber => [$homeSourceNumber, $awaySourceNumber]) {
            $match = Matche::where('match_number', $matchNumber)->first();
            if (! $match) {
                $this->error("Partido #{$matchNumber} no encontrado.");
                $errors++;
                continue;
            }

            $homeSource = Matche::where('match_number', $homeSourceNumber)->value('id');
            $awaySource = Matche::where('match_number', $awaySourceNumber)->value('id');

            if (! $homeSource || ! $awaySource) {
                $this->error("Partido #{$matchNumber}: no se encontró source #{$homeSourceNumber} o #{$awaySourceNumber}.");
                $errors++;
                continue;
            }

            $this->line(sprintf(
                'Partido #%d: home_source %d→%d (partido #%d)  |  away_source %d→%d (partido #%d)',
                $matchNumber,
                $match->home_source_match_id, $homeSource, $homeSourceNumber,
                $match->away_source_match_id, $awaySource, $awaySourceNumber
            ));

            if (! $dryRun) {
                $match->update([
                    'home_source_match_id' => $homeSource,
                    'away_source_match_id' => $awaySource,
                ]);
            }
        }

        if ($errors > 0) {
            $this->error("{$errors} error(es) encontrado(s). Revisá la base de datos.");
            return self::FAILURE;
        }

        $this->info($dryRun ? 'Dry run completado.' : 'Cruces actualizados correctamente.');
        return self::SUCCESS;
    }
}
