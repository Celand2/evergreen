@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Referral Program</h2>
    
    <!-- Referral Code -->
    <div class="bg-gray-50 p-4 rounded mb-6">
        <p class="text-sm text-gray-600 mb-2">Your Referral Code</p>
        <div class="flex items-center">
            <input type="text" value="{{ auth()->user()->referral_code }}" readonly 
                class="flex-1 px-3 py-2 border border-gray-300 rounded bg-white" id="referralCode">
            <button onclick="copyReferralCode()" class="ml-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                📋
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-green-50 p-4 rounded text-center">
            <p class="text-3xl font-bold text-green-600">{{ $stats['level1_count'] }}</p>
            <p class="text-sm text-gray-600">Level 1 Referrals</p>
            <p class="text-xs text-gray-500 mt-1">11% commission</p>
        </div>
        <div class="bg-blue-50 p-4 rounded text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $stats['level2_count'] }}</p>
            <p class="text-sm text-gray-600">Level 2 Referrals</p>
            <p class="text-xs text-gray-500 mt-1">3% commission</p>
        </div>
        <div class="bg-purple-50 p-4 rounded text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $stats['level3_count'] }}</p>
            <p class="text-sm text-gray-600">Level 3 Referrals</p>
            <p class="text-xs text-gray-500 mt-1">1% commission</p>
        </div>
    </div>

    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mb-6">
        <p class="text-sm text-gray-600">Total Commission Earned</p>
        <p class="text-2xl font-bold text-yellow-600">${{ number_format($stats['total_commission'], 2) }}</p>
        <p class="text-sm text-gray-500">{{ auth()->user()->toLocal($stats['total_commission']) }}</p>
    </div>

    <!-- Referral Lists -->
    @foreach(['level1', 'level2', 'level3'] as $level)
        @if($referrals[$level]->count() > 0)
            <div class="mb-6">
                <h3 class="text-xl font-bold mb-4">Level {{ substr($level, -1) }} Referrals</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Name</th>
                                <th class="px-4 py-2 text-left">Phone</th>
                                <th class="px-4 py-2 text-left">Joined</th>
                                <th class="px-4 py-2 text-left">Commission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referrals[$level] as $referral)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ $referral->referred->name }}</td>
                                    <td class="px-4 py-2">{{ $referral->referred->phone }}</td>
                                    <td class="px-4 py-2">{{ $referral->referred->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="space-y-1">
                                            <div>${{ number_format($referral->commission, 2) }}</div>
                                            <div class="text-xs text-gray-500">{{ auth()->user()->toLocal($referral->commission) }}</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endforeach
</div>

@push('scripts')
<script>
function copyReferralCode() {
    const copyText = document.getElementById("referralCode");
    copyText.select();
    document.execCommand("copy");
    alert("Referral code copied!");
}
</script>
@endpush
@endsection