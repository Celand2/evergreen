<?php

namespace App\Services;

use App\Models\DailyGain;
use App\Models\ExchangeRate;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserVip;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class DailyGainService
{
    /**
     * Credit every gain that is due 24 hours after a VIP purchase and then at
     * each following 24-hour interval.
     *
     * The unique database constraint on (user_vip_id, date) makes this safe to
     * run more than once for the same day.
     */
    public function process(CarbonInterface $date): int
    {
        $processed = 0;
        $now = $date->copy();

        UserVip::query()
            ->with(['user:id,currency', 'vip:id,duration_days'])
            ->withCount('dailyGains')
            ->where('status', 'active')
            ->where('started_at', '<=', $now->copy()->subDay())
            ->orderBy('id')
            ->chunkById(100, function ($userVips) use (&$processed, $now) {
                foreach ($userVips as $userVip) {
                    $durationDays = $userVip->duration_days ?? $userVip->vip->duration_days;
                    $elapsedDays = intdiv($userVip->started_at->diffInSeconds($now), 86400);
                    $dueGains = min($durationDays, $elapsedDays);

                    for ($period = $userVip->daily_gains_count + 1; $period <= $dueGains; $period++) {
                        $gainDate = $userVip->started_at->copy()->addDays($period)->toDateString();

                        if ($this->creditGain($userVip, $gainDate)) {
                            $processed++;
                        }
                    }

                    if ($dueGains >= $durationDays) {
                        $userVip->update(['status' => 'expired']);
                    }
                }
            });

        return $processed;
    }

    private function creditGain(UserVip $userVip, string $gainDate): bool
    {
        $amountUsd = round((float) $userVip->daily_gain, 2);
        $currency = $userVip->user->currency ?: 'USD';
        $rate = $this->rateFor($currency);

        if ($rate === null) {
            $currency = 'USD';
            $rate = 1.0;
        }

        $amountLocal = round($amountUsd * $rate, 2);

        return DB::transaction(function () use ($userVip, $gainDate, $amountUsd, $amountLocal, $currency, $rate) {
            $inserted = DB::table('daily_gains')->insertOrIgnore([
                'user_id' => $userVip->user_id,
                'user_vip_id' => $userVip->id,
                'amount' => $amountUsd,
                'amount_usd' => $amountUsd,
                'amount_local' => $amountLocal,
                'currency' => $currency,
                'rate_used' => $rate,
                'date' => $gainDate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted === 0) {
                return false;
            }

            $user = User::query()->lockForUpdate()->findOrFail($userVip->user_id);
            $user->increment('balance_retirable', $amountUsd);

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Daily VIP gain credited',
                'body' => 'Your daily VIP gain of $' . number_format($amountUsd, 2) . ' has been credited.',
            ]);

            return true;
        });
    }

    private function rateFor(string $currency): ?float
    {
        if ($currency === 'USD') {
            return 1.0;
        }

        $rate = ExchangeRate::query()
            ->active()
            ->where('currency', $currency)
            ->latest('date')
            ->latest('id')
            ->value('rate_to_usd');

        return $rate && (float) $rate > 0 ? (float) $rate : null;
    }
}
