@extends('layouts.admin')

@section('title', 'Edit Exchange Rate')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">Edit Exchange Rate</h2>
        <a href="{{ route('admin.exchange-rates.index') }}" class="text-gray-600 hover:text-gray-900">Back to rates</a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.exchange-rates.update', $exchangeRate) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Currency (5 letters)</label>
                    <input id="currency" type="text" name="currency" value="{{ old('currency', $exchangeRate->currency) }}" maxlength="5" required class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="rate_to_usd" class="block text-gray-700 text-sm font-bold mb-2">Rate to USD</label>
                    <input id="rate_to_usd" type="number" name="rate_to_usd" value="{{ old('rate_to_usd', $exchangeRate->rate_to_usd) }}" step="0.000001" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                    <input id="date" type="date" name="date" value="{{ old('date', $exchangeRate->date->format('Y-m-d')) }}" required class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
            </div>

            <label class="flex items-center mb-6">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $exchangeRate->is_active) ? 'checked' : '' }} class="mr-2">
                <span class="text-gray-700">Active</span>
            </label>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save changes</button>
        </form>
    </div>
</div>
@endsection
