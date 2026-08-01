@extends('layouts.client')

@section('content')
<div class="client-section rounded-xl p-4 mb-4 border border-gray-700"
    style="background: radial-gradient(ellipse at top right, rgba(32,251,3,0.07) 0%, #1f2937 80%);">
    <h2 class="text-white font-semibold text-sm mb-4">Make a Withdrawal</h2>

    <form action="{{ route('client.withdrawals.store') }}" method="POST" class="mb-8">
        @csrf

        <div class="mb-3">
            <label for="payment_method_id" class="block text-gray-400 text-[11px] mb-1">Payment Method</label>
            <select name="payment_method_id" id="payment-method-id" required
                class="w-full bg-gray-900 border border-gray-700 text-white text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition"
                onchange="updateWithdrawalDetails()">
                <option value="">Select Payment Method</option>
                @foreach($paymentMethods as $method)
                <option value="{{ $method->id }}"
                    data-rate="{{ optional($method->exchangeRate)->rate_to_usd ?? 1 }}"
                    data-currency="{{ optional($method->exchangeRate)->currency ?? 'USD' }}"
                    data-account-number="{{ $method->account_number }}"
                    data-account-name="{{ $method->account_name }}"
                    data-logo="{{ $method->logo ? asset('storage/' . $method->logo) : '' }}"
                    data-method-name="{{ $method->name }}">
                    {{ $method->name }}
                </option>
                @endforeach
            </select>

            <div id="withdrawal-payment-preview" class="mt-2 hidden items-center gap-3 rounded-lg border border-gray-700 bg-gray-900/60 p-2">
                <img id="withdrawal-payment-logo" src="" alt="Payment method logo"
                    class="w-12 h-12 md:w-14 md:h-14 object-cover rounded-lg border border-gray-700 bg-white/5">
                <div>
                    <p class="text-[10px] text-gray-500">Selected method</p>
                    <p id="withdrawal-payment-name" class="text-xs font-semibold text-white">-</p>
                </div>
            </div>
        </div>

        <div class="mb-3 text-xs text-gray-500">
            <p id="withdrawal-account-number">Account: -</p>
            <p id="withdrawal-account-name">Name: -</p>
            <p id="withdrawal-currency-display">Currency: {{ auth()->user()->currency ?? 'USD' }}</p>
        </div>

        <input type="hidden" name="currency" id="withdrawal-currency" value="{{ auth()->user()->currency ?? 'USD' }}">

        <div class="mb-3">
            <label for="amount-local" class="block text-gray-400 text-[11px] mb-1">Amount in local currency</label>
            <input type="number" name="amount_local" id="amount-local" step="0.01" min="1" required
                class="w-full bg-gray-900 border border-gray-700 text-white text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition"
                placeholder="Enter amount in local currency"
                oninput="updateWithdrawalDetails()">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4 text-xs text-gray-400">
            <div class="client-section bg-gray-900 border border-gray-700 rounded-lg p-3">
                <p class="text-gray-500 mb-1">USD Equivalent</p>
                <p id="withdrawal-usd" class="text-white">$0.00</p>
            </div>
            <div class="client-section bg-gray-900 border border-gray-700 rounded-lg p-3">
                <p class="text-gray-500 mb-1">Fee (10%)</p>
                <p id="withdrawal-fee" class="text-white">$0.00</p>
            </div>
            <div class="client-section bg-gray-900 border border-gray-700 rounded-lg p-3 md:col-span-2">
                <p class="text-gray-500 mb-1">You receive</p>
                <p id="withdrawal-received" class="text-[#20fb03]">$0.00</p>
            </div>
        </div>

        <div class="mb-4">
            <label for="account_number" class="block text-gray-400 text-[11px] mb-1">Account Number</label>
            <input type="text" name="account_number" id="account_number" required
                class="w-full bg-gray-900 border border-gray-700 text-white text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition">
        </div>

        <div class="mb-6">
            <label for="account_name" class="block text-gray-400 text-[11px] mb-1">Account Name</label>
            <input type="text" name="account_name" id="account_name" required
                class="w-full bg-gray-900 border border-gray-700 text-white text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition">
        </div>
        <p class="text-[10px] text-gray-500 mb-3">
            Minimum withdrawal: {{ auth()->user()->currency ? auth()->user()->toLocal(0.7) : '$0.70 USD' }}
        </p>
        <button type="submit"
            class="w-full bg-[#20fb03] text-gray-900 font-semibold py-2.5 rounded-lg hover:opacity-90 transition">
            💸 Request Withdrawal
        </button>
    </form>

    <h3 class="text-white font-semibold text-sm mb-3">Withdrawal History</h3>
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
                    <td class="px-4 py-2">
                        @if(auth()->user()->currency)
                        {{ auth()->user()->toLocal($withdrawal->amount_usd) }}
                        @else
                        ${{ number_format($withdrawal->amount_usd, 2) }} USD
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if(auth()->user()->currency)
                        {{ auth()->user()->toLocal($withdrawal->fee) }}
                        @else
                        ${{ number_format($withdrawal->fee, 2) }} USD
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if(auth()->user()->currency)
                        {{ auth()->user()->toLocal($withdrawal->amount_received) }}
                        @else
                        ${{ number_format($withdrawal->amount_received, 2) }} USD
                        @endif
                    </td>
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

@push('scripts')
<script>
    function updateWithdrawalDetails() {
        const paymentSelect = document.getElementById('payment-method-id');
        const selectedOption = paymentSelect?.options[paymentSelect.selectedIndex];
        const rate = parseFloat(selectedOption?.dataset.rate) || 1;
        const currency = selectedOption?.dataset.currency || '{{ auth()->user()->currency ?? 'USD' }}';
        const accountNumber = selectedOption?.dataset.accountNumber || '-';
        const accountName = selectedOption?.dataset.accountName || '-';
        const logo = selectedOption?.dataset.logo || '';
        const methodName = selectedOption?.dataset.methodName || '-';
        const localAmount = parseFloat(document.getElementById('amount-local')?.value) || 0;

        document.getElementById('withdrawal-account-number').textContent = 'Account: ' + accountNumber;
        document.getElementById('withdrawal-account-name').textContent = 'Name: ' + accountName;
        document.getElementById('withdrawal-currency-display').textContent = 'Currency: ' + currency;
        document.getElementById('withdrawal-currency').value = currency;

        const previewWrapper = document.getElementById('withdrawal-payment-preview');
        const previewLogo = document.getElementById('withdrawal-payment-logo');
        const previewName = document.getElementById('withdrawal-payment-name');

        if (selectedOption && selectedOption.value) {
            previewWrapper.classList.remove('hidden');
            previewWrapper.classList.add('flex');
            previewLogo.src = logo;
            previewLogo.alt = methodName + ' logo';
            previewName.textContent = methodName;
        } else {
            previewWrapper.classList.add('hidden');
            previewWrapper.classList.remove('flex');
            previewLogo.src = '';
            previewName.textContent = '-';
        }

        const usd = rate > 0 ? localAmount / rate : 0;
        const fee = usd * 0.10;
        const received = usd * 0.90;

        document.getElementById('withdrawal-usd').textContent = '$' + usd.toFixed(2);
        document.getElementById('withdrawal-fee').textContent = '$' + fee.toFixed(2);
        document.getElementById('withdrawal-received').textContent = '$' + received.toFixed(2);

        const submitBtn = document.querySelector('button[type="submit"]');
        if (usd > 0 && usd < 0.7) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.textContent = '⚠️ Minimum $0.70 required';
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            submitBtn.textContent = '💸 Request Withdrawal';
        }
    }

    document.addEventListener('DOMContentLoaded', updateWithdrawalDetails);
</script>
@endpush
@endsection