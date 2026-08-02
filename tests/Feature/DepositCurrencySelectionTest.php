<?php

namespace Tests\Feature;

use App\Models\Deposit;
use App\Models\ExchangeRate;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepositCurrencySelectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_uses_the_selected_payment_method_currency_and_updates_the_user_currency(): void
    {
        $user = User::create([
            'name' => 'Deposit User',
            'phone' => '0970000009',
            'email' => 'deposit-user@example.test',
            'password' => 'password',
            'referral_code' => 'DPTUSER1',
            'currency' => 'USD',
            'balance_investissable' => 0,
            'balance_retirable' => 0,
        ]);

        $paymentMethod = PaymentMethod::create([
            'name' => 'Airtel Money',
            'account_number' => '111',
            'account_name' => 'Airtel Pay',
            'is_active' => true,
        ]);

        ExchangeRate::create([
            'payment_method_id' => $paymentMethod->id,
            'currency' => 'ZMW',
            'rate_to_usd' => 25.5,
            'date' => today(),
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->post(route('client.deposits.store'), [
                'payment_method_id' => $paymentMethod->id,
                'amount_local' => 2550,
            ])
            ->assertRedirect(route('client.deposits.index'));

        $this->assertDatabaseHas('deposits', [
            'user_id' => $user->id,
            'payment_method_id' => $paymentMethod->id,
            'amount_local' => 2550,
            'currency' => 'ZMW',
            'rate_used' => 25.5,
            'status' => 'pending',
        ]);

        $this->assertSame('ZMW', $user->fresh()->currency);
        $this->assertSame(100.0, (float) Deposit::first()->amount_usd);
    }
}
