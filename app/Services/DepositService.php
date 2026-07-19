<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\User;
use App\Services\ReferralService;

class DepositService
{
    public function approve(Deposit $deposit): void
    {
        $user = $deposit->user;

        // Add amount to balance_investissable
        $user->balance_investissable += $deposit->amount;
        
        // Set currency if null (first deposit)
        if (is_null($user->currency)) {
            $user->currency = $deposit->currency;
        }
        
        $user->save();

        // Update deposit status
        $deposit->status = 'approved';
        $deposit->approved_at = now();
        $deposit->save();

        // Trigger referral commissions
        app(ReferralService::class)->processCommissions($deposit);
    }
}