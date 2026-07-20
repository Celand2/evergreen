<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::with('exchangeRate')->paginate(20);
        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $exchangeRates = ExchangeRate::active()->get();
        return view('admin.payment-methods.create', compact('exchangeRates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'account_number'   => ['nullable', 'string'],
            'account_name'     => ['nullable', 'string'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'is_active'        => ['nullable', 'boolean'],
            'exchange_rate_id' => ['required', 'exists:exchange_rates,id'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('payment-methods', 'public');
        }

        $paymentMethod = PaymentMethod::create([
            'name'           => $validated['name'],
            'account_number' => $validated['account_number'] ?? null,
            'account_name'   => $validated['account_name'] ?? null,
            'logo'           => $validated['logo'] ?? null,
            'is_active'      => $validated['is_active'],
        ]);

        // Lier l'exchange rate au payment method
        ExchangeRate::where('id', $validated['exchange_rate_id'])
            ->update(['payment_method_id' => $paymentMethod->id]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        $exchangeRates = ExchangeRate::active()->get();
        return view('admin.payment-methods.edit', compact('paymentMethod', 'exchangeRates'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'account_number'   => ['nullable', 'string'],
            'account_name'     => ['nullable', 'string'],
            'logo'             => ['nullable', 'image', 'max:2048'],
            'is_active'        => ['nullable', 'boolean'],
            'exchange_rate_id' => ['required', 'exists:exchange_rates,id'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('payment-methods', 'public');
        }

        $paymentMethod->update([
            'name'           => $validated['name'],
            'account_number' => $validated['account_number'] ?? null,
            'account_name'   => $validated['account_name'] ?? null,
            'logo'           => $validated['logo'] ?? null,
            'is_active'      => $validated['is_active'],
        ]);

        // Détacher l'ancien exchange rate de ce payment method
        ExchangeRate::where('payment_method_id', $paymentMethod->id)
            ->update(['payment_method_id' => null]);

        // Lier le nouveau
        ExchangeRate::where('id', $validated['exchange_rate_id'])
            ->update(['payment_method_id' => $paymentMethod->id]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        // Détacher l'exchange rate avant suppression
        ExchangeRate::where('payment_method_id', $paymentMethod->id)
            ->update(['payment_method_id' => null]);

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }
}