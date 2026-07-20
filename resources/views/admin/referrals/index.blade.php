@extends('layouts.admin')

@section('title', 'Referrals Management')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">Referrer</th>
                <th class="px-4 py-2 text-left">Referred User</th>
                <th class="px-4 py-2 text-left">Level</th>
                <th class="px-4 py-2 text-left">Commission</th>
                <th class="px-4 py-2 text-left">Local Commission</th>
                <th class="px-4 py-2 text-left">Date</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($referrals as $referral)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $referral->referrer->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $referral->referred->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-100 text-purple-800">
                            Level {{ $referral->level }}
                        </span>
                    </td>
                    <td class="px-4 py-2">${{ number_format($referral->commission, 2) }}</td>
                    <td class="px-4 py-2">
                        {{ $referral->referrer?->currency ? $referral->referrer->toLocal($referral->commission) : '-' }}
                    </td>
                    <td class="px-4 py-2">{{ $referral->created_at->format('Y-m-d') }}</td>
                    <td class="px-4 py-2">
                        <form action="{{ route('admin.referrals.destroy', $referral) }}" method="POST" class="inline">
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

<div class="mt-4">
    {{ $referrals->links() }}
</div>
@endsection