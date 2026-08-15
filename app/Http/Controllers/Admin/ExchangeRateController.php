<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $exchangeRates = ExchangeRate::paginate(20);

        return view('admin.exchange-rates.index', compact('exchangeRates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', 'size:3'],
            'rate_to_usd' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);

        $validated['currency'] = strtoupper($validated['currency']);
        $validated['is_active'] = $request->has('is_active');

        ExchangeRate::create($validated);

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate created successfully.');
    }

    public function update(Request $request, ExchangeRate $exchangeRate)
    {
        $request->merge([
            'currency' => $request->input('currency', $exchangeRate->currency),
            'date' => $request->input('date', $exchangeRate->date->format('Y-m-d')),
        ]);

        $validated = $request->validate([
            'currency' => ['required', 'string', 'size:3'],
            'rate_to_usd' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);

        $validated['currency'] = strtoupper($validated['currency']);
        $validated['is_active'] = $request->has('is_active');

        $exchangeRate->update($validated);

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate updated successfully.');
    }

    public function edit(ExchangeRate $exchangeRate)
    {
        return view('admin.exchange-rates.edit', compact('exchangeRate'));
    }

    public function destroy(ExchangeRate $exchangeRate)
    {
        $exchangeRate->delete();

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate deleted successfully.');
    }
}
