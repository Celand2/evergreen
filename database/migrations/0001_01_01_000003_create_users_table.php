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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique(); // login principal
            $table->string('email')->nullable()->unique();
            $table->string('country')->nullable();
            $table->string('avatar')->nullable();
            $table->string('password');
            $table->string('role')->default('user');
            $table->string('status')->default('active');
            $table->string('referral_code')->unique();
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('balance_investissable', 10, 2)->default(0);
            $table->decimal('balance_retirable', 10, 2)->default(10);
            $table->foreignId('preferred_payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('currency')->nullable();
            $table->decimal('preferred_rate', 10, 6)->nullable();
            $table->decimal('balance_investissable_local', 10, 2)->default(0);
            $table->decimal('balance_retirable_local', 10, 2)->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('phone')->primary(); // on utilise le téléphone
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
