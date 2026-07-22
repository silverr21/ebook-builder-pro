<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ai_provider')->default('openai')->after('ai_enabled');
            $table->string('ai_temperature')->default('0.7')->after('ai_provider');
            $table->integer('ai_max_tokens')->default(2000)->after('ai_temperature');
            $table->integer('ai_timeout')->default(60)->after('ai_max_tokens');
            $table->string('ai_language')->default('sk')->after('ai_timeout');
            $table->string('ai_api_url')->nullable()->after('ai_language');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ai_provider',
                'ai_temperature',
                'ai_max_tokens',
                'ai_timeout',
                'ai_language',
                'ai_api_url',
            ]);
        });
    }
};
