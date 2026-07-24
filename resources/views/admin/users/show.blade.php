@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">User Details</h2>
    
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <p class="text-sm text-gray-600">Name</p>
            <p class="font-semibold">{{ $user->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Phone</p>
            <p class="font-semibold">{{ $user->phone }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Email</p>
            <p class="font-semibold">{{ $user->email ?? 'N/A' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Country</p>
            <p class="font-semibold">{{ $user->country }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Balance Investable</p>
            <p class="font-semibold text-green-600">${{ number_format($user->balance_investissable, 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Balance Retirable</p>
            <p class="font-semibold text-blue-600">${{ number_format($user->balance_retirable, 2) }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Status</p>
            <span class="px-2 py-1 rounded text-xs font-semibold
                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($user->status) }}
            </span>
        </div>
        <div>
            <p class="text-sm text-gray-600">Referral Code</p>
            <p class="font-semibold">{{ $user->referral_code }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Active Referrals</p>
            <p class="font-semibold">{{ $user->activeReferralsCount() }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Sponsor Tier</p>
            <p class="font-semibold">{{ $user->currentSponsorTier()->name }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-600">Review Status</p>
            @if($user->is_frozen)
                <span class="inline-flex rounded bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">FROZEN - Under Review</span>
            @else
                <span class="inline-flex rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">Clear</span>
            @endif
        </div>
    </div>

    <div class="mb-6">
        <h3 class="text-xl font-bold mb-4">Update Status</h3>
        <form action="{{ route('admin.users.updateStatus', $user) }}" method="POST">
            @csrf
            <div class="flex gap-4">
                <select name="status" class="px-3 py-2 border border-gray-300 rounded">
                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Update Status
                </button>
            </div>
        </form>
    </div>

    <div class="mb-6">
        <h3 class="text-xl font-bold mb-4">Update Balance</h3>
        <form action="{{ route('admin.users.updateBalance', $user) }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Balance Investable</label>
                    <input type="number" name="balance_investissable" step="0.01" value="{{ $user->balance_investissable }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Balance Retirable</label>
                    <input type="number" name="balance_retirable" step="0.01" value="{{ $user->balance_retirable }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Update Balance
            </button>
        </form>
    </div>

    <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 p-4">
        <h3 class="text-xl font-bold mb-4">Lucky Wheel</h3>
        <form method="POST" action="{{ route('admin.users.grantSpin', $user) }}">
            @csrf
            @if($user->lucky_wheel_available)
                <button type="button" disabled class="bg-gray-200 text-gray-500 px-4 py-2 rounded cursor-not-allowed">
                    Spin already granted
                </button>
            @else
                <button type="submit" class="bg-[#a4fb03] text-gray-900 font-semibold px-4 py-2 rounded hover:opacity-90">
                    Grant Lucky Wheel Spin
                </button>
            @endif
        </form>
    </div>

    <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800">Back to Users List</a>
</div>
@endsection