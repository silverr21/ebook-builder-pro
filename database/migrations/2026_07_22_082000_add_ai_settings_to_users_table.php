<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ai_api_key')->nullable()->after('two_factor_enabled');
            $table->string('ai_model')->default('gpt-4-turbo')->after('ai_api_key');
            $table->boolean('ai_enabled')->default(true)->after('ai_model');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ai_api_key', 'ai_model', 'ai_enabled']);
        });
    }
};
