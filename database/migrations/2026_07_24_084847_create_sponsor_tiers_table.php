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
    Schema::create('sponsor_tiers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('badge_emoji')->nullable();
        $table->integer('min_actives');
        $table->decimal('bonus_usd', 10, 2);
        $table->decimal('commission_l1', 5, 2);
        $table->decimal('commission_l2', 5, 2);
        $table->decimal('commission_l3', 5, 2);
        $table->integer('order');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sponsor_tiers');
    }
};
