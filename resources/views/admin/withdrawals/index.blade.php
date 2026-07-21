@extends('layouts.admin')

@section('title', 'Withdrawals')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Fee</th>
                    <th class="px-4 py-2 text-left">Received</th>
                    <th class="px-4 py-2 text-left">Payment Method</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($withdrawals as $withdrawal)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $withdrawal->user->name }}</td>
                    <td class="px-4 py-2">
                        ${{ number_format($withdrawal->amount_usd, 2) }}
                        @if($withdrawal->currency)
                            / {{ number_format($withdrawal->amount_local, 2) }} {{ $withdrawal->currency }}
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        ${{ number_format($withdrawal->fee, 2) }}
                        @if($withdrawal->currency)
                            / {{ number_format($withdrawal->amount_local * 0.10, 2) }} {{ $withdrawal->currency }}
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        ${{ number_format($withdrawal->amount_received, 2) }}
                        @if($withdrawal->currency)
                            / {{ number_format($withdrawal->amount_local * 0.90, 2) }} {{ $withdrawal->currency }}
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $withdrawal->paymentMethod->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($withdrawal->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $withdrawal->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">
                        @if($withdrawal->status === 'pending')
                            <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-800 mr-2">✅ Approve</button>
                            </form>
                            <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-red-600 hover:text-red-800 mr-2">❌ Reject</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.withdrawals.destroy', $withdrawal) }}" method="POST" class="inline">
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
    {{ $withdrawals->links() }}
</div>
@endsection