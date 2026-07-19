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
        Schema::create('daily_gains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_vip_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_usd', 10, 2);
            $table->decimal('amount_local', 10, 2);
            $table->string('currency');
            $table->decimal('rate_used', 10, 6);
            $table->date('date');
            $table->unique(['user_vip_id', 'date']); // 1 gain par vip par jour
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_gains');
    }
};
