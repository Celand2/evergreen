<?php

namespace App\Services;

use App\Models\Deposit;
use App\Services\ReferralService;

class DepositService
{
    public function approve(Deposit $deposit): void
    {
        $user = $deposit->user;

        // Add amount_usd to balance_investissable
        $user->balance_investissable += $deposit->amount_usd;

        // Set currency if null (first deposit)
        if (is_null($user->currency)) {
            $user->currency = $deposit->currency;
        }

        $user->save();

        // Update deposit status
        $deposit->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        // Trigger referral commissions
        app(ReferralService::class)->processCommissions($deposit);
    }
}