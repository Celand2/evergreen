@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">My VIPs</h2>

    @if($activeVips->isNotEmpty())
        <div class="grid grid-cols-1 gap-6">
            @foreach($activeVips as $userVip)
                @php
                    $creditedCount = $userVip->dailyGains()->count();
                    $nextCreditAt = $userVip->started_at->copy()->addDays($creditedCount + 1);
                @endphp
                <div class="border rounded-lg p-6 bg-gray-50 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold">{{ $userVip->vip->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $userVip->vip->description }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-green-600 text-white text-xs uppercase">Active</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Invested:</span>
                                <span class="font-semibold">{{ auth()->user()->toLocal($userVip->amount_invested) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Daily gain:</span>
                                <span class="font-semibold text-green-700">{{ auth()->user()->toLocal($userVip->daily_gain) }}</span>
                            </div>
                        </div>

                        <div class="bg-white border border-green-200 rounded-lg p-3">
                            <p class="text-xs text-gray-500 mb-1">Next gain in:</p>
                            <p class="text-lg font-bold text-green-700 font-mono"
                               data-countdown="{{ $nextCreditAt->toIso8601String() }}"
                               id="countdown-{{ $userVip->id }}">
                                --:--:--
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-6 text-yellow-800">
            You don't have any active VIP yet. Go to the VIP tab to choose a plan.
        </div>
    @endif
</div>

@push('scripts')
<script>
    function updateCountdowns() {
        document.querySelectorAll('[data-countdown]').forEach(el => {
            const target = new Date(el.dataset.countdown).getTime();
            const now = new Date().getTime();
            let diff = target - now;

            if (diff <= 0) {
                el.textContent = 'Crediting soon...';
                return;
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            el.textContent =
                String(hours).padStart(2, '0') + ':' +
                String(minutes).padStart(2, '0') + ':' +
                String(seconds).padStart(2, '0');
        });
    }

    updateCountdowns();
    setInterval(updateCountdowns, 1000);
</script>
@endpush
@endsection
