@extends('layouts.admin')

@section('title', 'Sponsor Tiers')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Super-Sponsor Tiers</h2>
    </div>

    {{-- Add new tier --}}
    <details class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
        <summary class="cursor-pointer font-bold text-gray-700 text-sm">+ Add New Tier</summary>
        <form action="{{ route('admin.sponsor-tiers.store') }}" method="POST" class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Name</label>
                <input type="text" name="name" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Badge Emoji</label>
                <input type="text" name="badge_emoji" maxlength="10" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Min Active Referrals</label>
                <input type="number" name="min_actives" required min="0" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Bonus (USD)</label>
                <input type="number" name="bonus_usd" step="0.01" required min="0" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Commission L1 (%)</label>
                <input type="number" name="commission_l1" step="0.01" required min="0" max="100" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Commission L2 (%)</label>
                <input type="number" name="commission_l2" step="0.01" required min="0" max="100" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Commission L3 (%)</label>
                <input type="number" name="commission_l3" step="0.01" required min="0" max="100" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Order</label>
                <input type="number" name="order" required min="1" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
            </div>
            <div class="col-span-2 md:col-span-4">
                <button type="submit"
                    class="bg-[#a4fb03] text-gray-900 font-bold py-2 px-6 rounded hover:opacity-90 transition text-sm">
                    Create Tier
                </button>
            </div>
        </form>
    </details>

    {{-- Tiers list --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-3 py-3">Order</th>
                    <th class="px-3 py-3">Badge</th>
                    <th class="px-3 py-3">Name</th>
                    <th class="px-3 py-3">Min Actives</th>
                    <th class="px-3 py-3">Bonus (USD)</th>
                    <th class="px-3 py-3">L1 / L2 / L3</th>
                    <th class="px-3 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tiers as $tier)
                    <tr>
                        <td class="px-3 py-3 text-gray-500">{{ $tier->order }}</td>
                        <td class="px-3 py-3 text-lg">{{ $tier->badge_emoji }}</td>
                        <td class="px-3 py-3 font-semibold text-gray-900">{{ $tier->name }}</td>
                        <td class="px-3 py-3">{{ $tier->min_actives }}</td>
                        <td class="px-3 py-3 text-[#3f7d00] font-semibold">${{ number_format($tier->bonus_usd, 2) }}</td>
                        <td class="px-3 py-3 text-gray-500">
                            {{ $tier->commission_l1 }}% / {{ $tier->commission_l2 }}% / {{ $tier->commission_l3 }}%
                        </td>
                        <td class="px-3 py-3 text-right">
                            <button type="button"
                                onclick="document.getElementById('edit-{{ $tier->id }}').classList.toggle('hidden')"
                                class="text-gray-600 text-xs font-semibold hover:underline">
                                Edit
                            </button>
                            <form action="{{ route('admin.sponsor-tiers.destroy', $tier) }}" method="POST" class="inline"
                                onsubmit="return confirm('Delete tier {{ $tier->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-xs font-semibold hover:underline ml-2">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    <tr id="edit-{{ $tier->id }}" class="hidden bg-gray-50">
                        <td colspan="7" class="px-3 py-4">
                            <form action="{{ route('admin.sponsor-tiers.update', $tier) }}" method="POST" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Name</label>
                                    <input type="text" name="name" value="{{ $tier->name }}" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Badge</label>
                                    <input type="text" name="badge_emoji" value="{{ $tier->badge_emoji }}" class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Min Actives</label>
                                    <input type="number" name="min_actives" value="{{ $tier->min_actives }}" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Bonus (USD)</label>
                                    <input type="number" step="0.01" name="bonus_usd" value="{{ $tier->bonus_usd }}" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">L1 (%)</label>
                                    <input type="number" step="0.01" name="commission_l1" value="{{ $tier->commission_l1 }}" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">L2 (%)</label>
                                    <input type="number" step="0.01" name="commission_l2" value="{{ $tier->commission_l2 }}" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">L3 (%)</label>
                                    <input type="number" step="0.01" name="commission_l3" value="{{ $tier->commission_l3 }}" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 mb-1">Order</label>
                                    <input type="number" name="order" value="{{ $tier->order }}" required class="w-full px-2 py-1.5 border border-gray-300 rounded text-sm">
                                </div>
                                <div class="col-span-2 md:col-span-4">
                                    <button type="submit" class="bg-gray-800 text-white text-xs font-semibold px-4 py-2 rounded hover:bg-gray-700 transition">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">No tiers yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection