@extends('layouts.client')

@section('content')

{{-- Form --}}
<div class="client-section rounded-xl p-4 mb-4 border border-gray-700"
     style="background: radial-gradient(ellipse at top right, rgba(32,251,3,0.07) 0%, #1f2937 80%);">
    <h2 class="text-white font-semibold text-sm mb-4">Make a Deposit</h2>

    <form action="{{ route('client.deposits.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Payment Method --}}
        <div class="mb-3">
            <label class="block text-gray-400 text-[11px] mb-1">Payment Method</label>
            <select name="payment_method_id" id="payment-method-select" required
                class="w-full bg-gray-900 border border-gray-700 text-white text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition"
                onchange="updateDepositDetails()">
                <option value="">Select a method</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method->id }}"
                        data-rate="{{ optional($method->exchangeRate)->rate_to_usd ?? 1 }}"
                        data-currency="{{ optional($method->exchangeRate)->currency ?? ($user->currency ?? 'USD') }}"
                        data-account-number="{{ $method->account_number }}"
                        data-account-name="{{ $method->account_name }}">
                        {{ $method->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3 text-xs text-gray-500">
            <p id="deposit-account-number">Account: -</p>
            <p id="deposit-account-name">Name: -</p>
            <p id="deposit-currency">Currency: {{ $user->currency ?? 'Select a method' }}</p>
        </div>

        <input type="hidden" name="currency" id="deposit-currency-input" value="{{ $user->currency ?? '' }}">

        {{-- Amount Local --}}
        <div class="mb-3">
            <label class="block text-gray-400 text-[11px] mb-1">Amount in local currency</label>
            <input type="number" name="amount_local" id="amount-local" step="0.01" min="1" required
                class="w-full bg-gray-900 border border-gray-700 text-white text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition"
                placeholder="Enter amount in local currency"
                oninput="updateDepositDetails()">
        </div>

        {{-- USD Equivalent --}}
        <div class="mb-3">
            <label class="block text-gray-400 text-[11px] mb-1">Equivalent in USD</label>
            <div id="amount-usd-display"
                class="w-full bg-gray-900 border border-gray-700 text-[#20fb03] text-xs rounded-lg px-3 py-2">
                $0.00 USD
            </div>
        </div>

        {{-- Proof --}}
        <div class="mb-4">
            <label class="block text-gray-400 text-[11px] mb-1">Payment Proof (image)</label>
            <input type="file" name="proof" accept="image/*"
                class="w-full bg-gray-900 border border-gray-700 text-gray-400 text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition">
        </div>

        <button type="submit"
            class="w-full bg-[#20fb03] text-gray-900 text-xs font-semibold py-2.5 rounded-lg hover:opacity-90 transition">
            Submit Deposit
        </button>
    </form>
</div>

{{-- History --}}
<div class="client-section rounded-xl p-4 border border-gray-700"
     style="background: radial-gradient(ellipse at bottom left, rgba(32,251,3,0.05) 0%, #1f2937 80%);">
    <h3 class="text-white font-semibold text-sm mb-3">Deposit History</h3>

    @forelse($deposits as $deposit)
        <div class="flex justify-between items-center py-2 border-b border-gray-700 last:border-0">
            <div>
                <p class="text-white text-xs font-medium">
                    @if(auth()->user()->currency)
                        {{ auth()->user()->toLocal($deposit->amount_usd) }}
                    @else
                        ${{ number_format($deposit->amount_usd, 2) }} USD
                    @endif
                </p>
                <p class="text-gray-500 text-[10px]">{{ $deposit->created_at->format('d M Y') }}</p>
            </div>
            <span class="text-[10px] font-semibold px-2 py-1 rounded-full
                {{ $deposit->status === 'approved' ? 'bg-[#20fb03] text-gray-900' :
                   ($deposit->status === 'rejected' ? 'bg-red-500/20 text-red-400' : 'bg-yellow-500/20 text-yellow-400') }}">
                {{ ucfirst($deposit->status) }}
            </span>
        </div>
    @empty
        <p class="text-gray-500 text-xs text-center py-4">No deposits yet.</p>
    @endforelse

    <div class="mt-3">{{ $deposits->links() }}</div>
</div>

@endsection

@push('scripts')
<script>
    function updateDepositDetails() {
        const paymentSelect = document.getElementById('payment-method-select');
        const selectedOption = paymentSelect?.options[paymentSelect.selectedIndex];
        const rate = parseFloat(selectedOption?.dataset.rate) || 1;
        const optionCurrency = selectedOption?.dataset.currency || '{{ $user->currency ?? 'USD' }}';
        const currency = '{{ $user->currency ?? '' }}' || optionCurrency;
        const accountNumber = selectedOption?.dataset.accountNumber || '-';
        const accountName = selectedOption?.dataset.accountName || '-';
        const localAmount = parseFloat(document.getElementById('amount-local')?.value) || 0;

        document.getElementById('deposit-account-number').textContent = 'Account: ' + accountNumber;
        document.getElementById('deposit-account-name').textContent = 'Name: ' + accountName;
        document.getElementById('deposit-currency').textContent = 'Currency: ' + currency;

        const usd = rate > 0 ? localAmount / rate : 0;
        document.getElementById('amount-usd-display').textContent =
            '$' + usd.toFixed(2) + ' USD';
        document.getElementById('deposit-currency-input').value = currency;
    }

    document.addEventListener('DOMContentLoaded', updateDepositDetails);
</script>
@endpush
