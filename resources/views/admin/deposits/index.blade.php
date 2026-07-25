@extends('layouts.admin')

@section('title', 'Deposits')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Amount (USD)</th>
                    <th class="px-4 py-2 text-left">Payment Method</th>
                    <th class="px-4 py-2 text-left">Proof</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($deposits as $deposit)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $deposit->user->name }}</td>
                    <td class="px-4 py-2">
                        ${{ number_format($deposit->amount_usd, 2) }}
                        @if($deposit->currency)
                            / {{ number_format($deposit->amount_local, 2) }} {{ $deposit->currency }}
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $deposit->paymentMethod->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        @if($deposit->proof)
                            <button type="button"
                                onclick="openProofModal('{{ asset('storage/' . $deposit->proof) }}')"
                                class="block">
                                <img src="{{ asset('storage/' . $deposit->proof) }}"
                                     alt="Payment proof"
                                     class="w-12 h-12 object-cover rounded border border-gray-200 hover:opacity-80 transition">
                            </button>
                        @else
                            <span class="text-gray-400 text-xs">No proof</span>
                        @endif
                    </td>
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
</div>

<div class="mt-4">
    {{ $deposits->links() }}
</div>

{{-- Proof Preview Modal --}}
<div id="proofModal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center p-4" onclick="closeProofModal(event)">
    <div class="relative max-w-2xl w-full max-h-[90vh]" onclick="event.stopPropagation()">
        <button type="button"
            onclick="closeProofModal()"
            class="absolute -top-10 right-0 text-white text-3xl leading-none hover:text-gray-300">
            &times;
        </button>
        <img id="proofModalImage" src="" alt="Payment proof full view"
             class="w-full h-auto max-h-[85vh] object-contain rounded-lg bg-white">
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openProofModal(src) {
        const modal = document.getElementById('proofModal');
        const img = document.getElementById('proofModalImage');
        img.src = src;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeProofModal(event) {
        const modal = document.getElementById('proofModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeProofModal();
    });
</script>
@endpush