@extends('layouts.admin')

@section('title', 'Withdrawals')

@section('content')

<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
    <h2 class="text-xl font-bold text-gray-900">Withdrawals</h2>

    <div class="flex flex-wrap gap-2">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by user ID or phone"
                class="px-3 py-1.5 border border-gray-300 rounded text-xs w-48 focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
            <button type="submit" class="bg-gray-800 text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-gray-700 transition">
                🔍
            </button>
            @if(request('search'))
                <a href="{{ route('admin.withdrawals.index') }}" class="text-xs text-gray-500 self-center hover:underline">Clear</a>
            @endif
        </form>

        <button type="button"
            onclick="copyAllPending()"
            class="bg-green-800 text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-gray-700 transition whitespace-nowrap">
            📋 Copy all pending
        </button>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[850px] w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left">User</th>
                    <th class="px-3 py-2 text-left">Recipient</th>
                    <th class="px-3 py-2 text-left">Amount</th>
                    <th class="px-3 py-2 text-left">Payment Method</th>
                    <th class="px-3 py-2 text-left">Status</th>
                    <th class="px-3 py-2 text-left">Date</th>
                    <th class="px-3 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($withdrawals as $withdrawal)
                <tr class="border-b">
                    <td class="px-3 py-2">
                        {{ $withdrawal->user->name }}
                        <p class="text-[10px] text-gray-400">ID: {{ $withdrawal->user->id }} · {{ $withdrawal->user->phone }}</p>
                    </td>
                    <td class="px-3 py-2">
                        <p class="font-medium">{{ $withdrawal->account_name }}</p>
                        <p class="text-xs text-gray-500">{{ $withdrawal->account_number }}</p>
                    </td>
                    <td class="px-3 py-2 font-semibold text-gray-900">
                        {{ number_format($withdrawal->amount_local * 0.90, 2) }} {{ $withdrawal->currency }}
                    </td>
                    <td class="px-3 py-2">{{ $withdrawal->paymentMethod->name ?? 'N/A' }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $withdrawal->status === 'approved' ? 'bg-green-100 text-green-800' : 
                               ($withdrawal->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($withdrawal->status) }}
                        </span>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap">{{ $withdrawal->created_at->format('Y-m-d') }}</td>
                    <td class="px-3 py-2">
                        <div class="flex flex-wrap gap-1">
                            @if($withdrawal->status === 'pending')
                                <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST">
                                    @csrf
                                    <button type="submit" title="Approve"
                                        class="text-green-600 hover:bg-green-50 rounded px-1.5 py-1 text-xs">✅</button>
                                </form>
                                <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST">
                                    @csrf
                                    <button type="submit" title="Reject"
                                        class="text-red-600 hover:bg-red-50 rounded px-1.5 py-1 text-xs">❌</button>
                                </form>
                                <button type="button" title="Copy"
                                    onclick="copyOne(this)"
                                    data-name="{{ $withdrawal->account_name }}"
                                    data-number="{{ $withdrawal->account_number }}"
                                    data-amount="{{ number_format($withdrawal->amount_local * 0.90, 2) }} {{ $withdrawal->currency }}"
                                    class="text-gray-600 hover:bg-gray-100 rounded px-1.5 py-1 text-xs">📋</button>
                            @endif
                            <form action="{{ route('admin.withdrawals.destroy', $withdrawal) }}" method="POST"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Delete"
                                    class="text-red-600 hover:bg-red-50 rounded px-1.5 py-1 text-xs">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">No withdrawals found.</td>
                </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $withdrawals->links() }}
</div>

<script id="pending-withdrawals-data" type="application/json">
    [
        @foreach($withdrawals->where('status', 'pending') as $withdrawal)
            {
                "name": {{ Illuminate\Support\Js::from($withdrawal->account_name) }},
                "number": {{ Illuminate\Support\Js::from($withdrawal->account_number) }},
                "amount": {{ Illuminate\Support\Js::from(number_format($withdrawal->amount_local * 0.90, 2) . ' ' . $withdrawal->currency) }}
            }@if(!$loop->last),@endif
        @endforeach
    ]
</script>

@endsection

@push('scripts')
<script>
    function copyOne(button) {
        const name = button.dataset.name;
        const number = button.dataset.number;
        const amount = button.dataset.amount;

        const text = `Name: ${name}\nAccount number: ${number}\nAmount: ${amount}`;

        navigator.clipboard.writeText(text).then(() => {
            const original = button.textContent;
            button.textContent = '✅';
            setTimeout(() => button.textContent = original, 1200);
        });
    }

    function copyAllPending() {
        const data = JSON.parse(document.getElementById('pending-withdrawals-data').textContent);

        if (data.length === 0) {
            alert('No pending withdrawals to copy.');
            return;
        }

        const text = data.map((w, i) =>
            `#${i + 1}\nName: ${w.name}\nAccount number: ${w.number}\nAmount: ${w.amount}`
        ).join('\n\n');

        navigator.clipboard.writeText(text).then(() => {
            alert('Copied ' + data.length + ' pending withdrawal(s) to clipboard.');
        });
    }
</script>
@endpush