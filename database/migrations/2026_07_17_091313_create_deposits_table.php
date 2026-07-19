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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->decimal('amount_usd', 10, 2); // montant en USD
            $table->decimal('amount_local', 10, 2); // montant en monnaie locale
            $table->string('currency'); // ZMW, CDF...
            $table->decimal('rate_used', 10, 6); // taux au moment du dépôt
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->string('proof')->nullable(); // preuve de paiement
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
