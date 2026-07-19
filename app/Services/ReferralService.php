<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\Referral;
use App\Models\User;

class ReferralService
{
    public function processCommissions(Deposit $deposit): void
    {
        $user = $deposit->user;
        $amount = $deposit->amount;

        // Level 1: Direct referrer (11%)
        if ($user->referred_by) {
            $this->createCommission($user->referred_by, $amount, 0.11, 1, $user->id);

            // Level 2: Referrer's referrer (3%)
            $level2Referrer = User::find($user->referred_by)->referred_by ?? null;
            if ($level2Referrer) {
                $this->createCommission($level2Referrer, $amount, 0.03, 2, $user->id);
            }

            // Level 3: Level 2's referrer (1%)
            if ($level2Referrer) {
                $level3Referrer = User::find($level2Referrer)->referred_by ?? null;
                if ($level3Referrer) {
                    $this->createCommission($level3Referrer, $amount, 0.01, 3, $user->id);
                }
            }
        }
    }

    private function createCommission(User $referrer, float $amount, float $percentage, int $level, int $referredId): void
    {
        $commission = $amount * $percentage;

        // Add to balance_retirable
        $referrer->balance_retirable += $commission;
        $referrer->save();

        // Create referral record
        Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referredId,
            'commission' => $commission,
            'level' => $level,
        ]);
    }
}