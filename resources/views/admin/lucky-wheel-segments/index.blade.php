@extends('layouts.admin')

@section('title', 'Lucky Wheel Segments')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Lucky Wheel Segments</h2>
    </div>

    {{-- Add new segment --}}
    <form action="{{ route('admin.lucky-wheel-segments.store') }}" method="POST" class="flex items-end gap-3 mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
        @csrf
        <div class="flex-1">
            <label class="block text-gray-700 text-sm font-bold mb-2">Amount (USD)</label>
            <input type="number" name="amount_usd" step="0.01" min="0.01" required
                placeholder="e.g. 0.5"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
        </div>
        <button type="submit"
            class="bg-[#a4fb03] text-gray-900 font-bold py-2 px-6 rounded hover:opacity-90 transition">
            Add Segment
        </button>
    </form>

    {{-- Segments list --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-3">Amount (USD)</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($segments as $segment)
                    <tr>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.lucky-wheel-segments.update', $segment) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" name="amount_usd" step="0.01" min="0.01"
                                    value="{{ $segment->amount_usd }}"
                                    class="w-24 px-2 py-1 border border-gray-300 rounded text-sm">
                        </td>
                        <td class="px-4 py-3">
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="is_active" value="1"
                                        {{ $segment->is_active ? 'checked' : '' }}
                                        class="w-4 h-4 accent-[#a4fb03]">
                                    <span class="text-xs {{ $segment->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                        {{ $segment->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                        </td>
                        <td class="px-4 py-3 text-right">
                                <button type="submit"
                                    class="bg-gray-800 text-white text-xs font-semibold px-3 py-1.5 rounded hover:bg-gray-700 transition">
                                    Save
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-8 text-center text-gray-400">No segments yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Delete actions (separate forms, outside the edit form to avoid nested forms) --}}
    <div class="mt-4 flex flex-wrap gap-2">
        @foreach($segments as $segment)
            <form action="{{ route('admin.lucky-wheel-segments.destroy', $segment) }}" method="POST"
                onsubmit="return confirm('Delete this segment ({{ $segment->amount_usd }} USD)?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 text-xs hover:underline">
                    Delete {{ $segment->amount_usd }} USD
                </button>
            </form>
        @endforeach
    </div>
</div>
@endsection