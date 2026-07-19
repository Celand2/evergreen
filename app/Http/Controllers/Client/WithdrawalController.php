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
        $validated = $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'amount' => ['required', 'numeric', 'min:10'],
            'account_number' => ['required', 'string'],
            'account_name' => ['required', 'string'],
        ]);

        $user = auth()->user();

        // Check if user has enough balance_retirable
        if ($user->balance_retirable < $validated['amount']) {
            return back()->with('error', 'Insufficient balance.');
        }

        app(WithdrawalService::class)->process($user, $validated);

        return redirect()->route('client.withdrawals.index')->with('success', 'Withdrawal request submitted successfully.');
    }
}