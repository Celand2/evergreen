<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\ExchangeRate;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $deposits = $user->deposits()->latest()->paginate(20);
        $paymentMethods = PaymentMethod::active()->with('exchangeRate')->get();

        return view('client.deposits.index', compact('deposits', 'paymentMethods', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'amount_local'      => ['required', 'numeric', 'min:1'],
            'proof'             => ['nullable', 'image', 'max:2048'],
        ]);

        $user = auth()->user();
        $paymentMethod = PaymentMethod::with('exchangeRate')->findOrFail($validated['payment_method_id']);
        $exchangeRate = $paymentMethod->exchangeRate;
        $rateUsed = $exchangeRate ? $exchangeRate->rate_to_usd : 1;
        $amountUsd = $rateUsed > 0 ? ceil(($validated['amount_local'] / $rateUsed) * 100) / 100 : 0;
        $currency = $user->currency ?: ($exchangeRate?->currency ?? 'USD');

        if ($request->hasFile('proof')) {
            $validated['proof'] = $request->file('proof')->store('deposits', 'public');
        }

        Deposit::create([
            'user_id'           => $user->id,
            'payment_method_id' => $validated['payment_method_id'],
            'amount_usd'        => $amountUsd,
            'amount_local'      => $validated['amount_local'],
            'currency'          => $currency,
            'rate_used'         => $rateUsed,
            'proof'             => $validated['proof'] ?? null,
            'status'            => 'pending',
        ]);

        // Fixer la devise du user si pas encore définie
        if (!$user->currency) {
            $user->update(['currency' => $currency]);
        }

        return redirect()->route('client.deposits.index')
            ->with('success', 'Deposit submitted successfully. Awaiting approval.');
    }
}
