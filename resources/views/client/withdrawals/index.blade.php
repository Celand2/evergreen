@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Make a Withdrawal</h2>
    
    <form action="{{ route('client.withdrawals.store') }}" method="POST" class="mb-8">
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
            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount (USD)</label>
            <input type="number" name="amount" id="amount" step="0.01" min="10" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            <p class="text-sm text-gray-500 mt-1">Minimum withdrawal: $10.00</p>
            <p class="text-sm text-gray-500">Fee: 10% | You receive: 90%</p>
        </div>

        <div class="mb-4">
            <label for="account_number" class="block text-gray-700 text-sm font-bold mb-2">Account Number</label>
            <input type="text" name="account_number" id="account_number" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-6">
            <label for="account_name" class="block text-gray-700 text-sm font-bold mb-2">Account Name</label>
            <input type="text" name="account_name" id="account_name" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <button type="submit" 
            class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
            💸 Request Withdrawal
        </button>
    </form>

    <h3 class="text-xl font-bold mb-4">Withdrawal History</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Fee</th>
                    <th class="px-4 py-2 text-left">Received</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $withdrawal)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $withdrawal->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">${{ number_format($withdrawal->amount, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($withdrawal->fee, 2) }}</td>
                        <td class="px-4 py-2">${{ number_format($withdrawal->amount_received, 2) }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($withdrawal->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection