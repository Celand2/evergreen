<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\PaymentMethod;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = auth()->user()->withdrawals()->paginate(20);
        $paymentMethods = PaymentMethod::active()->get();
        return view('client.withdrawals.index', compact('withdrawals', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->is_frozen) {
        return back()->with('error', 'Your account is under review. Withdrawals are temporarily disabled.');
        }

        $validated = $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'amount_local' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'account_name' => ['required', 'string'],
        ]);

        $user = auth()->user();
        $paymentMethod = PaymentMethod::with('exchangeRate')->findOrFail($validated['payment_method_id']);
        $exchangeRate = $paymentMethod->exchangeRate;
        $rateUsed = $exchangeRate ? $exchangeRate->rate_to_usd : 1;
        $amountUsd = $rateUsed > 0 ? $validated['amount_local'] / $rateUsed : 0;

        if ($amountUsd < 0.5) {
            return back()->with('error', 'Withdrawal amount must be at least $0.500 USD.');
        }

        if ($user->balance_retirable < $amountUsd) {
            return back()->with('error', 'Insufficient balance.');
        }

        $validated['amount_usd'] = $amountUsd;
        $validated['rate_used'] = $rateUsed;

        app(WithdrawalService::class)->process($user, $validated);

        return redirect()->route('client.withdrawals.index')->with('success', 'Withdrawal request submitted successfully.');
    }
}