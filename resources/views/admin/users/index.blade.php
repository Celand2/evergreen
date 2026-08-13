@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')

<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
    <h2 class="text-xl font-bold text-gray-900">Users</h2>

    <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search by ID, phone or name"
            class="px-3 py-2 border border-gray-300 rounded text-sm w-64 focus:outline-none focus:ring-2 focus:ring-[#a4fb03]">
        <button type="submit" class="bg-gray-800 text-white text-xs font-semibold px-4 py-2 rounded hover:bg-gray-700 transition">
            🔍 Search
        </button>
        @if(request('search'))
            <a href="{{ route('admin.users.index') }}" class="text-xs text-gray-500 self-center hover:underline">Clear</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Phone</th>
                    <th class="px-4 py-2 text-left">Country</th>
                    <th class="px-4 py-2 text-left">Balance Investable</th>
                    <th class="px-4 py-2 text-left">Balance Retirable</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr class="border-b">
                    <td class="px-4 py-2 text-gray-400">#{{ $user->id }}</td>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->phone }}</td>
                    <td class="px-4 py-2">{{ $user->country }}</td>
                    <td class="px-4 py-2">${{ number_format($user->balance_investissable, 2) }}</td>
                    <td class="px-4 py-2">${{ number_format($user->balance_retirable, 2) }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-800 mr-2">👁️ View</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">🗑️ Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">No users found.</td>
                </tr>
            @endforelse
        </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection