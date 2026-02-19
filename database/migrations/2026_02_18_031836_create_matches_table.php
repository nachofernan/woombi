<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_group_id')->nullable()->constrained('tournament_groups')->nullOnDelete();
            $table->foreignId('home_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('away_team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->foreignId('home_source_match_id')->nullable()->constrained('matches')->nullOnDelete();
            $table->foreignId('away_source_match_id')->nullable()->constrained('matches')->nullOnDelete();
            $table->enum('home_source_result', ['ganador', 'perdedor'])->nullable();
            $table->enum('away_source_result', ['ganador', 'perdedor'])->nullable();
            $table->enum('stage', ['fase_grupos', 'octavos', 'cuartos', 'semis', 'tercero', 'final']);
            $table->unsignedSmallInteger('match_number');
            $table->dateTime('match_date')->nullable();
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->unsignedTinyInteger('home_extra_score')->nullable();
            $table->unsignedTinyInteger('away_extra_score')->nullable();
            $table->foreignId('penalty_winner_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->enum('status', ['pendiente', 'en_juego', 'finalizado'])->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
