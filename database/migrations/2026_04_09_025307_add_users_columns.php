<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_chat_id')->nullable()->after('champion_updated_at');
            $table->string('telegram_token', 6)->nullable()->after('telegram_chat_id');
            $table->timestamp('telegram_token_expires_at')->nullable()->after('telegram_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_chat_id', 'telegram_token', 'telegram_token_expires_at']);
        });
    }
};