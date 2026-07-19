@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Make a Deposit</h2>
    
    <form action="{{ route('client.deposits.store') }}" method="POST" enctype="multipart/form-data" class="mb-8">
        @csrf
        
        <div class="mb-4">
            <label for="payment_method_id" class="block text-gray-700 text-sm font-bold mb-2">Payment Method</label>
            <select name="payment_method_id" id="payment_method_id" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Select Payment Method</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}">{{ $method->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="amount_usd" class="block text-gray-700 text-sm font-bold mb-2">Amount (USD)</label>
            <input type="number" name="amount_usd" id="amount_usd" step="0.01" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-4">
            <label for="amount_local" class="block text-gray-700 text-sm font-bold mb-2">Amount (Local Currency)</label>
            <input type="number" name="amount_local" id="amount_local" step="0.01" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-4">
            <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Currency</label>
            <input type="text" name="currency" id="currency" maxlength="3" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-6">
            <label for="proof" class="block text-gray-700 text-sm font-bold mb-2">Payment Proof (Image)</label>
            <input type="file" name="proof" id="proof" accept="image/*"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <button type="submit" 
            class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
            💰 Submit Deposit
        </button>
    </form>

    <h3 class="text-xl font-bold mb-4">Deposit History</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Amount (USD)</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deposits as $deposit)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $deposit->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">${{ number_format($deposit->amount, 2) }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($deposit->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $deposits->links() }}
    </div>
</div>
@endsection