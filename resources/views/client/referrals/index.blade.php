@extends('layouts.client')

@section('content')

{{-- Referral Code --}}
<div class="rounded-xl p-4 mb-3 border border-gray-700"
     style="background: radial-gradient(ellipse at top right, rgba(164,251,3,0.07) 0%, #1f2937 80%);">
    <p class="text-gray-400 text-[11px] mb-2">Your referral code</p>
    <div class="flex items-center gap-2">
        <input type="text" value="{{ auth()->user()->referral_code }}" readonly id="referralCode"
            class="flex-1 bg-gray-900 border border-gray-700 text-[#a4fb03] text-sm font-semibold tracking-wider rounded-lg px-3 py-2">
        <button onclick="copyReferralCode()"
            class="bg-[#a4fb03] text-gray-900 text-xs font-semibold px-3 py-2 rounded-lg">
            📋 Copy
        </button>
    </div>
</div>

{{-- Tier & Commission rates --}}
<div class="rounded-xl p-4 mb-3 border border-gray-700"
     style="background: radial-gradient(ellipse at top left, rgba(164,251,3,0.07) 0%, #1f2937 80%);">
    <div class="flex justify-between items-center mb-2">
        <p class="text-white text-xs font-semibold">Your tier</p>
        <span class="text-[#a4fb03] text-sm font-semibold">{{ $currentTier->badge_emoji }} {{ $currentTier->name }}</span>
    </div>
    <p class="text-gray-500 text-[10px]">
        Commissions: {{ $currentTier->commission_l1 }}% · {{ $currentTier->commission_l2 }}% · {{ $currentTier->commission_l3 }}%
    </p>
    @if($nextTier)
        <div class="mt-3">
            <div class="w-full bg-gray-800 rounded-full h-1.5">
                <div class="bg-[#a4fb03] h-1.5 rounded-full"
                     style="width: {{ min(100, ($stats['active_count'] / max(1,$nextTier->min_actives)) * 100) }}%"></div>
            </div>
            <p class="text-gray-500 text-[10px] mt-1">
                {{ $stats['active_count'] }}/{{ $nextTier->min_actives }} active referrals to reach {{ $nextTier->name }}
            </p>
        </div>
    @endif
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-2 mb-3">
    <div class="rounded-xl p-3 border border-gray-700 text-center"
         style="background: radial-gradient(ellipse at bottom, rgba(164,251,3,0.05) 0%, #1f2937 80%);">
        <p class="text-lg font-bold text-[#a4fb03]">{{ $stats['level1_count'] }}</p>
        <p class="text-gray-500 text-[9px]">Level 1</p>
    </div>
    <div class="rounded-xl p-3 border border-gray-700 text-center"
         style="background: radial-gradient(ellipse at bottom, rgba(164,251,3,0.05) 0%, #1f2937 80%);">
        <p class="text-lg font-bold text-white">{{ $stats['level2_count'] }}</p>
        <p class="text-gray-500 text-[9px]">Level 2</p>
    </div>
    <div class="rounded-xl p-3 border border-gray-700 text-center"
         style="background: radial-gradient(ellipse at bottom, rgba(164,251,3,0.05) 0%, #1f2937 80%);">
        <p class="text-lg font-bold text-white">{{ $stats['level3_count'] }}</p>
        <p class="text-gray-500 text-[9px]">Level 3</p>
    </div>
</div>

{{-- Total commission --}}
<div class="rounded-xl p-4 mb-4 border border-gray-700"
     style="background: radial-gradient(ellipse at center, rgba(164,251,3,0.08) 0%, #1f2937 80%);">
    <p class="text-gray-400 text-[10px] mb-1">Total commission earned</p>
    <p class="text-xl font-bold text-[#a4fb03]">{{ auth()->user()->toLocal($stats['total_commission']) }}</p>
</div>

{{-- Team lists --}}
@foreach(['level1' => 'Level 1', 'level2' => 'Level 2', 'level3' => 'Level 3'] as $key => $label)
    @if($team[$key]->isNotEmpty())
        <div class="mb-4">
            <h3 class="text-white text-xs font-semibold mb-2">{{ $label }} — Team ({{ $team[$key]->count() }})</h3>
            <div class="space-y-2">
                @foreach($team[$key] as $member)
                    <div class="rounded-lg p-3 border border-gray-700 flex justify-between items-center"
                         style="background: #1f2937;">
                        <div>
                            <p class="text-white text-xs font-medium">{{ $member->name }}</p>
                            <p class="text-gray-500 text-[10px]">Joined {{ $member->created_at->format('d M Y') }}</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-1 rounded-full
                            {{ $member->qualifiesAsActive() ? 'bg-[#a4fb03] text-gray-900' : 'bg-gray-700 text-gray-400' }}">
                            {{ $member->qualifiesAsActive() ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endforeach

{{-- Commission History --}}
<div class="mb-4">
    <h3 class="text-white text-xs font-semibold mb-2">Commission History</h3>
    @forelse($commissions as $commission)
        <div class="rounded-lg p-3 border border-gray-700 flex justify-between items-center mb-2"
             style="background: #1f2937;">
            <div>
                <p class="text-white text-xs font-medium">{{ $commission->referred->name ?? 'N/A' }}</p>
                <p class="text-gray-500 text-[10px]">
                    Level {{ $commission->level }} · {{ $commission->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            <p class="text-[#a4fb03] text-xs font-semibold">
                +{{ auth()->user()->toLocal($commission->commission) }}
            </p>
        </div>
    @empty
        <p class="text-gray-500 text-xs text-center py-6">No commission earned yet.</p>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
function copyReferralCode() {
    const copyText = document.getElementById("referralCode");
    navigator.clipboard.writeText(copyText.value).then(() => {
        alert("Referral code copied!");
    });
}
</script>
@endpush