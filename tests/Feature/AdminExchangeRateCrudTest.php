<?php

namespace Tests\Feature;

use App\Models\ExchangeRate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminExchangeRateCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_perform_the_full_exchange_rate_crud(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'phone' => '0970000001',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
            'referral_code' => 'ADMIN001',
        ]);

        $this->actingAs($admin)->get(route('admin.exchange-rates.index'))->assertOk();

        $this->actingAs($admin)->post(route('admin.exchange-rates.store'), [
            'currency' => 'zmw',
            'rate_to_usd' => 25.5,
            'date' => '2026-08-15',
            'is_active' => true,
        ])->assertRedirect(route('admin.exchange-rates.index'));

        $this->assertDatabaseHas('exchange_rates', [
            'currency' => 'ZMW',
            'rate_to_usd' => 25.5,
            'date' => '2026-08-15',
            'is_active' => 1,
        ]);

        $exchangeRate = ExchangeRate::firstOrFail();

        $this->actingAs($admin)->get(route('admin.exchange-rates.edit', $exchangeRate))->assertOk();
        $this->actingAs($admin)->put(route('admin.exchange-rates.update', $exchangeRate), [
            'currency' => 'bif',
            'rate_to_usd' => 3000,
            'date' => '2026-08-16',
        ])->assertRedirect(route('admin.exchange-rates.index'));

        $this->assertDatabaseHas('exchange_rates', [
            'id' => $exchangeRate->id,
            'currency' => 'BIF',
            'rate_to_usd' => 3000,
            'date' => '2026-08-16',
            'is_active' => 0,
        ]);

        $this->actingAs($admin)->delete(route('admin.exchange-rates.destroy', $exchangeRate))
            ->assertRedirect(route('admin.exchange-rates.index'));

        $this->assertDatabaseMissing('exchange_rates', ['id' => $exchangeRate->id]);
    }

    public function test_non_admin_cannot_access_exchange_rate_management(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'phone' => '0970000002',
            'email' => 'user@example.test',
            'password' => 'password',
            'referral_code' => 'USER0001',
        ]);

        $this->actingAs($user)->get(route('admin.exchange-rates.index'))->assertRedirect('/login');
    }
}
