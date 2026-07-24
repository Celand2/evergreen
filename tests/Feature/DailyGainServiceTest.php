<?php

namespace Tests\Feature;

use App\Models\DailyGain;
use App\Models\ExchangeRate;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserVip;
use App\Models\Vip;
use App\Services\DailyGainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyGainServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_credits_an_eligible_vip_once_and_records_the_exchange_snapshot(): void
    {
        $user = User::create([
            'name' => 'Daily Gain User',
            'phone' => '0970000001',
            'email' => 'daily-gain@example.test',
            'password' => 'password',
            'referral_code' => 'DAILYGAIN1',
            'currency' => 'ZMW',
            'balance_investissable' => 0,
            'balance_retirable' => 0,
        ]);
        $vip = Vip::create([
            'name' => 'VIP 1',
            'price' => 25,
            'daily_percentage' => 5,
            'duration_days' => 30,
        ]);
        $userVip = UserVip::create([
            'user_id' => $user->id,
            'vip_id' => $vip->id,
            'amount_invested' => 25,
            'daily_gain' => 1.25,
            'started_at' => today()->subDay(),
            'expires_at' => today()->addDay(),
            'status' => 'active',
        ]);
        ExchangeRate::create([
            'currency' => 'ZMW',
            'rate_to_usd' => 25.5,
            'date' => today(),
            'is_active' => true,
        ]);

        $service = app(DailyGainService::class);

        $this->assertSame(1, $service->process(today()));
        $this->assertSame(0, $service->process(today()));

        $this->assertDatabaseHas('daily_gains', [
            'user_id' => $user->id,
            'user_vip_id' => $userVip->id,
            'amount' => 1.25,
            'amount_usd' => 1.25,
            'amount_local' => 31.88,
            'currency' => 'ZMW',
            'rate_used' => 25.5,
            'date' => today()->toDateString(),
        ]);
        $this->assertSame(1, DailyGain::count());
        $this->assertSame('1.25', $user->fresh()->balance_retirable);
        $this->assertSame(1, Notification::where('user_id', $user->id)->count());
    }

    public function test_it_does_not_credit_a_vip_on_its_purchase_day(): void
    {
        $user = User::create([
            'name' => 'New VIP User',
            'phone' => '0970000002',
            'email' => 'new-vip@example.test',
            'password' => 'password',
            'referral_code' => 'DAILYGAIN2',
            'balance_investissable' => 0,
            'balance_retirable' => 0,
        ]);
        $vip = Vip::create([
            'name' => 'VIP 2',
            'price' => 10,
            'daily_percentage' => 10,
            'duration_days' => 30,
        ]);
        UserVip::create([
            'user_id' => $user->id,
            'vip_id' => $vip->id,
            'amount_invested' => 10,
            'daily_gain' => 1,
            'started_at' => today(),
            'expires_at' => today()->addDays(30),
            'status' => 'active',
        ]);

        $this->assertSame(0, app(DailyGainService::class)->process(today()));
        $this->assertDatabaseCount('daily_gains', 0);
        $this->assertSame('0.00', $user->fresh()->balance_retirable);
    }
}
