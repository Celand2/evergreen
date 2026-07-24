<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_vips', function (Blueprint $table) {
            $table->dateTime('started_at')->change();
            $table->dateTime('expires_at')->change();
            $table->unsignedInteger('duration_days')->nullable()->after('daily_gain');
        });
    }

    public function down(): void
    {
        Schema::table('user_vips', function (Blueprint $table) {
            $table->dropColumn('duration_days');
            $table->date('started_at')->change();
            $table->date('expires_at')->change();
        });
    }
};
