@extends('layouts.client')

@section('content')

{{-- Hero Section --}}
<div class="client-section flex gap-3 rounded-xl p-3 mb-3 border border-gray-700"
    style="background: radial-gradient(ellipse at bottom right, rgba(32,251,3,0.08) 0%, #1f2937 80%);">
    {{-- Slide --}}
    <div class="flex-1 border-r border-gray-700 pr-3">
        <span class="inline-flex items-center gap-1 text-[10px] text-[#20fb03] font-medium uppercase tracking-wider mb-1">
            <span class="w-1.5 h-1.5 bg-[#20fb03] rounded-full animate-pulse"></span> Live
        </span>
        <p class="text-white font-semibold text-sm leading-tight mb-1">Invest & Earn Daily</p>
        <p class="text-gray-500 text-[10px]">Returns up to 8% per day</p>
        <div class="flex gap-1 mt-2">
            <div class="w-4 h-1 bg-[#20fb03] rounded-full"></div>
            <div class="w-2 h-1 bg-gray-600 rounded-full"></div>
            <div class="w-2 h-1 bg-gray-600 rounded-full"></div>
        </div>
    </div>
    {{-- Welcome --}}
    <div class="flex-1 flex flex-col justify-center pl-1">
        <p class="text-gray-500 text-[10px] mb-0.5">Welcome bonus</p>
        <p class="text-[#20fb03] font-semibold text-base">
            {{ $user->toLocal(0.5) }}
        </p>
        <p class="text-gray-600 text-[10px]">Free on signup</p>
    </div>
</div>

{{-- Balance Section --}}
<div class="client-section rounded-xl p-3 mb-3 border border-gray-700"
    style="background: radial-gradient(ellipse at top right, rgba(32,251,3,0.1) 0%, #1f2937 70%);">


    <div class="mt-3 space-y-2">
        <div class="rounded-lg border border-[#20fb03]/20 bg-black/20 p-2">
            <div class="flex items-center justify-between gap-2 mb-1">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">Available investable balance</p>
                <a href="{{ route('client.deposits.index') }}"
                    class="bg-[#20fb03] text-gray-900 text-[10px] font-semibold px-3 py-1.5 rounded-lg hover:opacity-90 transition">
                    Deposit
                </a>
            </div>
            <p class="text-2xl font-semibold text-[#20fb03]">
                {{ auth()->user()->toLocal($balance_investissable) }}
            </p>
        </div>

        <div class="rounded-lg border border-gray-700 bg-black/20 p-2">
            <div class="flex items-center justify-between gap-2 mb-1">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider">Withdrawable balance</p>
                <a href="{{ route('client.withdrawals.index') }}"
                    class="text-[#20fb03] text-[10px] font-semibold px-3 py-1 rounded-lg border border-[#20fb03] hover:bg-[#20fb03] hover:text-gray-900 transition">
                    Withdraw
                </a>
            </div>
            <p class="text-base font-bold text-white ">
                {{ auth()->user()->toLocal($balance_retirable) }}
            </p>
        </div>
    </div>

 
    <div class="flex justify-between items-start mb-3">

        <div class="flex justify-between items-center">
            <div>
                <p class="text-[10px] text-gray-500 mb-0.5">Today's earnings</p>
                <p class="text-xs font-medium text-[#20fb03]">
                    +{{ auth()->user()->toLocal($today_earnings) }}
                </p>
            </div>
        </div>


        
        <div class="text-right">
            <p class="text-[10px] text-gray-500 mb-0.5">Total earnings</p>
            <p class="text-sm font-medium text-white">
                {{ auth()->user()->toLocal($total_earnings) }}
            </p>
        </div>
    </div>
  

</div>

{{-- Check-in & Lucky Wheel --}}
<div class="grid grid-cols-2 gap-3 mb-3">

    {{-- Check-in --}}
    <div class="client-section rounded-xl p-3 border border-gray-700 text-center"
        style="background: radial-gradient(ellipse at bottom left, rgba(32,251,3,0.07) 0%, #1f2937 80%);">
        <svg class="w-6 h-6 text-[#20fb03] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        <p class="text-white text-xs font-medium mb-0.5">Check-in</p>
        <p class="text-[#20fb03] text-[10px] mb-2">
            +{{ auth()->user()->toLocal(0.025) }}
        </p>
        @if($has_checked_in_today)
        <button disabled
            class="w-full bg-gray-700 text-gray-500 text-[10px] font-medium py-1.5 rounded-lg cursor-not-allowed">
            Done ✓
        </button>
        @else
        <form method="POST" action="{{ route('client.checkin.store') }}">
            @csrf
            <button type="submit"
                class="w-full bg-[#20fb03] text-gray-900 text-[10px] font-semibold py-1.5 rounded-lg hover:opacity-90 transition">
                Claim
            </button>
        </form>
        @endif
    </div>

    {{-- Lucky Wheel --}}
    <div class="client-section rounded-xl p-3 border border-gray-700 text-center"
        style="background: radial-gradient(ellipse at bottom right, rgba(32,251,3,0.07) 0%, #1f2937 80%);">
        <p class="text-white text-xs font-medium mb-0.5">Lucky Wheel</p>
        <div class="relative mx-auto my-2 h-24 w-24">
            <span class="absolute -top-1 left-1/2 z-10 -translate-x-1/2 text-[#20fb03]">▼</span>
            <div id="lucky-wheel" class="lucky-wheel h-24 w-24 rounded-full border-4 border-[#20fb03] shadow-[0_0_18px_rgba(32,251,3,0.25)]">
                <div class="flex h-full w-full items-center justify-center rounded-full bg-gray-900/40 text-[9px] font-bold text-white">
                    <span>GOOD<br>LUCK</span>
                </div>
            </div>
        </div>
        <p id="lucky-wheel-status" class="text-gray-500 text-[10px] mb-2">Spin & win</p>
        @if(auth()->user()->lucky_wheel_available)
        <form id="lucky-wheel-form" method="POST" action="{{ route('client.lucky-wheel.spin') }}">
            @csrf
            <button id="lucky-wheel-button" type="submit" class="w-full bg-[#20fb03] text-gray-900 text-[10px] font-semibold py-1.5 rounded-lg hover:opacity-90 transition">
                Spin Now
            </button>
        </form>
        @else
        <button type="button" disabled
            class="w-full bg-gray-700 text-gray-500 text-[10px] font-semibold py-1.5 rounded-lg cursor-not-allowed">
            No spin available
        </button>
        @endif
    </div>
</div>

{{-- Earnings Simulator --}}
<!-- <div class="client-section rounded-xl p-3 mb-3 border border-gray-700"
     style="background: radial-gradient(ellipse at center, rgba(32,251,3,0.05) 0%, #1f2937 70%);">
    <p class="text-white text-xs font-medium mb-2">Earnings Simulator</p>
    <div class="flex items-center gap-2">
        <input
            type="number"
            id="sim-amount"
            placeholder="Enter amount"
            class="flex-1 bg-gray-900 border border-gray-700 text-white text-xs rounded-lg px-3 py-2 outline-none focus:border-[#20fb03] transition"
            oninput="calcSimulator(this.value)"
        />
        <span class="text-gray-600 text-xs">→</span>
        <span id="sim-result" class="text-[#20fb03] text-xs font-medium whitespace-nowrap">
            0.00 {{ auth()->user()->currency ?? 'USD' }}/day
        </span>
    </div>
    <p class="text-gray-600 text-[10px] mt-1">Based on 7% daily return</p>
</div> -->

{{-- Super-Sponsor Program --}}
<div class="client-section rounded-xl p-3 mb-3 border border-gray-700"
    style="background: radial-gradient(ellipse at top right, rgba(32,251,3,0.07) 0%, #1f2937 80%);">
    <div class="flex items-center justify-between gap-2">
        <p class="text-white text-xs font-semibold">SUPER-SPONSOR PROGRAM</p>
        <p class="text-[#20fb03] text-xs font-medium">
            {{ $current_tier->badge_emoji }} {{ $current_tier->name }}
        </p>
    </div>

    @if($next_tier)
    @php
    $tier_progress = min(100, ($active_referrals_count / max(1, $next_tier->min_actives)) * 100);
    @endphp
    <div class="mt-3 h-1.5 w-full rounded-full bg-gray-800">
        <div class="h-1.5 rounded-full bg-[#20fb03]" style="width: {{ $tier_progress }}%"></div>
    </div>
    <p class="mt-1 text-gray-400 text-[10px]">{{ $active_referrals_count }}/{{ $next_tier->min_actives }} active referrals</p>
    @else
    <p class="mt-3 text-[#20fb03] text-xs font-medium">You have reached the highest tier!</p>
    @endif

    <p class="mt-2 text-gray-500 text-[10px]">
        Your commissions: {{ $current_tier->commission_l1 }}% - {{ $current_tier->commission_l2 }}% - {{ $current_tier->commission_l3 }}%
    </p>
</div>

{{-- Support --}}
<div class="grid grid-cols-3 gap-2 mb-2">
    <a href="{{ route('client.guide') }}"
        class="client-section rounded-xl py-2 px-1 border border-gray-700 text-center hover:border-[#20fb03] transition"
        style="background: radial-gradient(ellipse at top, rgba(32,251,3,0.05) 0%, #1f2937 80%);">
        <svg class="w-5 h-5 text-[#20fb03] mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        <p class="text-gray-400 text-[10px]">Guide</p>
    </a>
    <a href="https://chat.whatsapp.com/KqIaXH7QRQ67Y3HfTUoqgk?s=cl&p=a&ilr=1"
        class="client-section rounded-xl py-2 px-1 border border-gray-700 text-center hover:border-[#20fb03] transition"
        style="background: radial-gradient(ellipse at top, rgba(32,251,3,0.05) 0%, #1f2937 80%);">
        <svg class="w-5 h-5 text-[#20fb03] mx-auto mb-1" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.11 1.522 5.84L0 24l6.336-1.496A11.942 11.942 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.854 0-3.6-.504-5.1-1.38l-.366-.216-3.762.888.916-3.666-.24-.378A9.944 9.944 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z" />
        </svg>
        <p class="text-gray-400 text-[10px]">WhatsApp</p>
    </a>
    <a href="#"
        class="client-section rounded-xl py-2 px-1 border border-gray-700 text-center hover:border-[#20fb03] transition"
        style="background: radial-gradient(ellipse at top, rgba(32,251,3,0.05) 0%, #1f2937 80%);">
        <svg class="w-5 h-5 text-[#20fb03] mx-auto mb-1" fill="currentColor" viewBox="0 0 24 24">
            <path d="M11.944 0A12 12 0 000 12a12 12 0 0012 12 12 12 0 0012-12A12 12 0 0012 0a12 12 0 00-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 01.171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
        </svg>
        <p class="text-gray-400 text-[10px]">Telegram</p>
    </a>
</div>

@endsection

@push('scripts')
<style>
    .lucky-wheel {
        background: conic-gradient(#20fb03 0deg 45deg,
                #374151 45deg 90deg,
                #20fb03 90deg 135deg,
                #374151 135deg 180deg,
                #20fb03 180deg 225deg,
                #374151 225deg 270deg,
                #20fb03 270deg 315deg,
                #374151 315deg 360deg);
        transition: transform 4s cubic-bezier(0.12, 0.78, 0.18, 1);
    }

    .lucky-wheel.is-spinning {
        transform: rotate(1800deg);
    }
</style>
<script>
    const luckyWheelForm = document.getElementById('lucky-wheel-form');

    if (luckyWheelForm) {
        luckyWheelForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const wheel = document.getElementById('lucky-wheel');
            const button = document.getElementById('lucky-wheel-button');
            const status = document.getElementById('lucky-wheel-status');

            wheel.classList.add('is-spinning');
            button.disabled = true;
            button.textContent = 'Spinning...';
            button.classList.add('cursor-not-allowed', 'opacity-60');
            status.textContent = 'The wheel is choosing your reward...';
            status.classList.remove('text-gray-500');
            status.classList.add('text-[#20fb03]');

            window.setTimeout(function() {
                luckyWheelForm.submit();
            }, 4100);
        });
    }

    function calcSimulator(amount) {
        const rate = 0.07;
        const daily = parseFloat(amount) * rate;
        const currency = '{{ auth()->user()->currency ?? "USD" }}';
        document.getElementById('sim-result').textContent =
            isNaN(daily) || daily <= 0 ?
            '0.00 ' + currency + '/day' :
            daily.toFixed(2) + ' ' + currency + '/day';
    }
</script>
@endpush