@extends('layouts.admin')

@section('title', 'Exchange Rates')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Exchange Rates</h2>
    <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Add New Rate
    </button>
</div>

<!-- Create Form -->
<div id="createForm" class="hidden bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">Add Exchange Rate</h3>
    <form action="{{ route('admin.exchange-rates.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Currency (5 letters)</label>
                <input type="text" name="currency" maxlength="5" required
                    class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Rate to USD</label>
                <input type="number" name="rate_to_usd" step="0.000001" required
                    class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Date</label>
                <input type="date" name="date" value="{{ today()->format('Y-m-d') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded">
            </div>
            <div>
                <label class="flex items-center mt-6">
                    <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                    <span class="text-gray-700">Active</span>
                </label>
            </div>
        </div>
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Rate</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Currency</th>
                    <th class="px-4 py-2 text-left">Rate to USD</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($exchangeRates as $rate)
                <tr class="border-b">
                    <td class="px-4 py-2 font-semibold">{{ $rate->currency }}</td>
                    <td class="px-4 py-2">{{ number_format($rate->rate_to_usd, 6) }}</td>
                    <td class="px-4 py-2">{{ $rate->date->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $rate->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $rate->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.exchange-rates.edit', $rate) }}" class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                        <form action="{{ route('admin.exchange-rates.update', $rate) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="rate_to_usd" value="{{ $rate->rate_to_usd }}">
                            <input type="hidden" name="is_active" value="{{ !$rate->is_active ? 1 : 0 }}">
                            <button type="submit" class="text-blue-600 hover:text-blue-800 mr-2">
                                {{ $rate->is_active ? '🔴 Deactivate' : '🟢 Activate' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.exchange-rates.destroy', $rate) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">🗑️ Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $exchangeRates->links() }}
</div>
@endsection
