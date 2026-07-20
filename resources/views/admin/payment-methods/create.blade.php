@extends('layouts.admin')

@section('title', 'Create Payment Method')

@section('content')
<div class="rounded-xl p-6 border border-gray-700 bg-white shadow">
    <h2 class="text-xl font-bold mb-6 text-gray-900">Create Payment Method</h2>

    <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Name --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
        </div>

        {{-- Account Number --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Account Number</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
        </div>

        {{-- Account Name --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Account Name</label>
            <input type="text" name="account_name" value="{{ old('account_name') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
        </div>

        {{-- Logo --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Logo</label>
            <input type="file" name="logo" accept="image/*"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
        </div>

        {{-- Exchange Rate --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Currency / Exchange Rate
            </label>
            @if($exchangeRates->isEmpty())
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-700 px-3 py-2 rounded text-sm">
                    No exchange rates found. 
                    <a href="{{ route('admin.exchange-rates.index') }}" class="underline font-semibold">Create one first</a>.
                </div>
            @else
                <select name="exchange_rate_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
                    <option value="">Select currency</option>
                    @foreach($exchangeRates as $rate)
                        <option value="{{ $rate->id }}" {{ old('exchange_rate_id') == $rate->id ? 'selected' : '' }}>
                            {{ $rate->currency }} — 1 USD = {{ number_format($rate->rate_to_usd, 2) }} {{ $rate->currency }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        {{-- Active --}}
        <div class="mb-6">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 accent-[#a4fb03]">
                <span class="text-gray-700 text-sm">Active</span>
            </label>
        </div>

        <button type="submit"
            class="w-full bg-[#a4fb03] text-gray-900 font-bold py-2.5 px-4 rounded-lg hover:opacity-90 transition">
            Create Payment Method
        </button>
    </form>
</div>
@endsection