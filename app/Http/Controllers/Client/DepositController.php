<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = auth()->user()->deposits()->paginate(20);
        $paymentMethods = PaymentMethod::active()->get();
        return view('client.deposits.index', compact('deposits', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'amount_usd' => ['required', 'numeric', 'min:1'],
            'amount_local' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', 'size:3'],
            'proof' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('proof')) {
            $validated['proof'] = $request->file('proof')->store('deposits', 'public');
        }

        // Get latest exchange rate
        $exchangeRate = \App\Models\ExchangeRate::where('currency', $validated['currency'])
            ->where('is_active', true)
            ->latest()
            ->first();

        $validated['user_id'] = auth()->id();
        $validated['rate_used'] = $exchangeRate ? $exchangeRate->rate_to_usd : 1;
        $validated['status'] = 'pending';

        Deposit::create($validated);

        return redirect()->route('client.deposits.index')->with('success', 'Deposit submitted successfully. Awaiting approval.');
    }
}