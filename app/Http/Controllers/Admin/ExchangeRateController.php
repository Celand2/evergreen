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
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['date'] = today();

        ExchangeRate::create($validated);

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate created successfully.');
    }

    public function update(Request $request, ExchangeRate $exchangeRate)
    {
        $validated = $request->validate([
            'rate_to_usd' => ['required', 'numeric', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $exchangeRate->update($validated);

        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate updated successfully.');
    }

    public function destroy(ExchangeRate $exchangeRate)
    {
        $exchangeRate->delete();
        return redirect()->route('admin.exchange-rates.index')->with('success', 'Exchange rate deleted successfully.');
    }
}