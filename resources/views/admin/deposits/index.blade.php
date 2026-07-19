@extends('layouts.admin')

@section('title', 'Deposits Management')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">User</th>
                <th class="px-4 py-2 text-left">Amount (USD)</th>
                <th class="px-4 py-2 text-left">Payment Method</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deposits as $deposit)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $deposit->user->name }}</td>
                    <td class="px-4 py-2">${{ number_format($deposit->amount, 2) }}</td>
                    <td class="px-4 py-2">{{ $deposit->paymentMethod->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $deposit->status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($deposit->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($deposit->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $deposit->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">
                        @if($deposit->status === 'pending')
                            <form action="{{ route('admin.deposits.approve', $deposit) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">✅ Approve</button>
                            </form>
                            <form action="{{ route('admin.deposits.reject', $deposit) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 mr-2">❌ Reject</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.deposits.destroy', $deposit) }}" method="POST" class="inline">
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

<div class="mt-4">
    {{ $deposits->links() }}
</div>
@endsection