<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_vips', function (Blueprint $table) {
            $table->timestamp('last_gain_credited_at')->nullable()->after('daily_gain');
        });
    }

    public function down(): void
    {
        Schema::table('user_vips', function (Blueprint $table) {
            $table->dropColumn('last_gain_credited_at');
        });
    }
};