<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->foreignId('predicted_winner_team_id')
                ->nullable()
                ->after('predicted_away_score')
                ->constrained('teams')
                ->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('champion_team_id')
                ->nullable()
                ->after('total_points')
                ->constrained('teams')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->dropForeign(['predicted_winner_team_id']);
            $table->dropColumn('predicted_winner_team_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['champion_team_id']);
            $table->dropColumn('champion_team_id');
        });
    }
};