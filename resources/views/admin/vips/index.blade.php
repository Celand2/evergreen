@extends('layouts.admin')

@section('title', 'VIPs Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">VIP Plans</h2>
    <a href="{{ route('admin.vips.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Add New VIP
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Daily %</th>
                    <th class="px-4 py-2 text-left">Duration</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($vips as $vip)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $vip->name }}</td>
                    <td class="px-4 py-2">${{ number_format($vip->price, 2) }}</td>
                    <td class="px-4 py-2">{{ $vip->daily_percentage }}%</td>
                    <td class="px-4 py-2">{{ $vip->duration_days }} days</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $vip->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $vip->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.vips.edit', $vip) }}" class="text-blue-600 hover:text-blue-800 mr-2">✏️ Edit</a>
                        <form action="{{ route('admin.vips.destroy', $vip) }}" method="POST" class="inline">
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
</div>

<div class="mt-4">
    {{ $vips->links() }}
</div>
@endsection