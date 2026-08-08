<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserVip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyGainService
{
    /**
     * Credit every gain due (24h since last credit, or since purchase if never
     * credited). Loops per VIP to catch up on any missed cycles in one run.
     */
    public function process(): int
    {
        $processed = 0;

        UserVip::with(['user:id,currency', 'vip:id,duration_days'])
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('last_gain_credited_at')
                    ->where('started_at', '<=', now()->subHours(24));
                $q->orWhere('last_gain_credited_at', '<=', now()->subHours(24));
            })
            ->orderBy('id')
            ->chunkById(100, function ($userVips) use (&$processed) {
                foreach ($userVips as $userVip) {
                    try {
                        $processed += $this->creditDueCycles($userVip);
                    } catch (\Throwable $e) {
                        Log::error('daily-gains: user_vip #' . $userVip->id . ' skipped', [
                            'user_id' => $userVip->user_id,
                            'error'   => $e->getMessage(),
                        ]);
                    }
                }
            });

        return $processed;
    }

    /**
     * Credit as many due 24h-cycles as needed for this VIP, in a single run
     * (catches up automatically if the cron missed a while).
     */
    private function creditDueCycles(UserVip $userVip): int
    {
        $credited = 0;
        $durationDays = $userVip->duration_days ?? $userVip->vip->duration_days;

        while (true) {
            $anchor = $userVip->last_gain_credited_at ?? $userVip->started_at;

            if ($anchor->diffInHours(now()) < 24) {
                break;
            }

            $cycleNumber = $userVip->dailyGains()->count() + 1;

            if ($cycleNumber > $durationDays) {
                $userVip->update(['status' => 'expired']);
                break;
            }

            $gainDate = $anchor->copy()->addDay();

            $created = $this->creditGain($userVip, $gainDate->toDateString());

            if (! $created) {
                // Already credited for that date somehow — stop to avoid an infinite loop.
                break;
            }

            $userVip->last_gain_credited_at = $gainDate;
            $userVip->save();
            $userVip->refresh();

            $credited++;
        }

        return $credited;
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
                'user_id'      => $userVip->user_id,
                'user_vip_id'  => $userVip->id,
                'amount'       => $amountUsd,
                'amount_usd'   => $amountUsd,
                'amount_local' => $amountLocal,
                'currency'     => $currency,
                'rate_used'    => $rate,
                'date'         => $gainDate,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            if ($inserted === 0) {
                return false;
            }

            $user = User::query()->lockForUpdate()->findOrFail($userVip->user_id);
            $user->increment('balance_retirable', $amountUsd);

            Notification::create([
                'user_id' => $user->id,
                'title'   => 'Daily VIP gain credited',
                'body'    => 'Your daily VIP gain of $' . number_format($amountUsd, 2) . ' has been credited.',
            ]);

            return true;
        });
    }

    private function rateFor(string $currency): ?float
    {
        if ($currency === 'USD') {
            return 1.0;
        }

        $rate = \App\Models\ExchangeRate::query()
            ->active()
            ->where('currency', $currency)
            ->latest('date')
            ->latest('id')
            ->value('rate_to_usd');

        return $rate && (float) $rate > 0 ? (float) $rate : null;
    }
}