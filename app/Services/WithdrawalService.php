<?php

namespace App\Services;

use App\Models\User;
use App\Models\Withdrawal;

class WithdrawalService
{
    public function process(User $user, array $data): Withdrawal
    {
        $amount = $data['amount'];
        $fee = $amount * 0.10;
        $amountReceived = $amount * 0.90;

        // Deduct from balance_retirable immediately
        $user->balance_retirable -= $amount;
        $user->save();

        // Create withdrawal
        return Withdrawal::create([
            'user_id' => $user->id,
            'payment_method_id' => $data['payment_method_id'],
            'amount' => $amount,
            'fee' => $fee,
            'amount_received' => $amountReceived,
            'account_number' => $data['account_number'],
            'account_name' => $data['account_name'],
            'status' => 'pending',
        ]);
    }

    public function approve(Withdrawal $withdrawal): void
    {
        $withdrawal->status = 'approved';
        $withdrawal->approved_at = now();
        $withdrawal->save();
    }

    public function reject(Withdrawal $withdrawal): void
    {
        // Refund amount to balance_retirable
        $user = $withdrawal->user;
        $user->balance_retirable += $withdrawal->amount;
        $user->save();

        $withdrawal->status = 'rejected';
        $withdrawal->save();
    }
}