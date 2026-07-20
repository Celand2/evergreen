@extends('layouts.client')

@section('content')
@php
    $userCurrency = auth()->user()->currency;
    $userRate = $userCurrency ? \App\Models\ExchangeRate::where('currency', $userCurrency)
        ->where('is_active', true)
        ->latest()
        ->value('rate_to_usd') : null;
@endphp
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Earnings Simulator</h2>
    <p class="text-gray-600 mb-6">Calculate your potential earnings with our VIP plans</p>
    
    <div class="mb-6">
        <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Enter Investment Amount (USD)</label>
        <input type="number" id="amount" step="0.01" min="1" 
            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500"
            placeholder="Enter amount">
        @if($userCurrency && $userRate)
            <p id="localAmountNote" class="text-sm text-gray-500 mt-2">Current rate: 1 USD = {{ number_format($userRate, 4) }} {{ $userCurrency }}</p>
        @else
            <p id="localAmountNote" class="text-sm text-gray-500 mt-2 hidden"></p>
        @endif
    </div>

    <div id="results" class="hidden">
        <h3 class="text-xl font-bold mb-4">Estimated Earnings</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($vips as $vip)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <h4 class="font-bold mb-2">{{ $vip->name }}</h4>
                    <div class="space-y-1 text-sm">
                        <p><span class="text-gray-600">Daily:</span> <span class="font-semibold text-green-600" id="daily-{{ $vip->id }}">$0.00</span></p>
                        <p><span class="text-gray-600">Weekly:</span> <span class="font-semibold text-blue-600" id="weekly-{{ $vip->id }}">$0.00</span></p>
                        <p><span class="text-gray-600">Monthly:</span> <span class="font-semibold text-purple-600" id="monthly-{{ $vip->id }}">$0.00</span></p>
                        <p><span class="text-gray-600">Total ({{ $vip->duration_days }} days):</span> <span class="font-semibold text-yellow-600" id="total-{{ $vip->id }}">$0.00</span></p>
                        @if($userCurrency && $userRate)
                            <p class="text-sm text-gray-500" id="local-daily-{{ $vip->id }}">Local daily: 0.00 {{ $userCurrency }}</p>
                            <p class="text-sm text-gray-500" id="local-total-{{ $vip->id }}">Local total: 0.00 {{ $userCurrency }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if($vips->count() == 0)
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <p class="text-gray-700">No VIP plans available at the moment. Please check back later.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const vipsData = @json($vips);

const userRate = @json($userRate);
const userCurrency = @json($userCurrency ?? '');

document.getElementById('amount').addEventListener('input', function(e) {
    const amount = parseFloat(e.target.value);
    const resultsDiv = document.getElementById('results');
    
    if (amount > 0) {
        resultsDiv.classList.remove('hidden');
        
        vipsData.forEach(vip => {
            const dailyGain = amount * (vip.daily_percentage / 100);
            const weeklyGain = dailyGain * 7;
            const monthlyGain = dailyGain * 30;
            const totalGain = dailyGain * vip.duration_days;
            
            document.getElementById(`daily-${vip.id}`).textContent = '$' + dailyGain.toFixed(2);
            document.getElementById(`weekly-${vip.id}`).textContent = '$' + weeklyGain.toFixed(2);
            document.getElementById(`monthly-${vip.id}`).textContent = '$' + monthlyGain.toFixed(2);
            document.getElementById(`total-${vip.id}`).textContent = '$' + totalGain.toFixed(2);

            if (userRate && userCurrency) {
                const localDaily = dailyGain * userRate;
                const localTotal = totalGain * userRate;
                const localDailyElement = document.getElementById(`local-daily-${vip.id}`);
                const localTotalElement = document.getElementById(`local-total-${vip.id}`);

                if (localDailyElement) {
                    localDailyElement.textContent = `Local daily: ${localDaily.toFixed(2)} ${userCurrency}`;
                }
                if (localTotalElement) {
                    localTotalElement.textContent = `Local total: ${localTotal.toFixed(2)} ${userCurrency}`;
                }
            }
        });
    } else {
        resultsDiv.classList.add('hidden');
    }
});
</script>
@endpush
