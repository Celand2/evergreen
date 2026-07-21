@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Payment Methods</h2>
    <a href="{{ route('admin.payment-methods.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Add New
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-[900px] w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Account Number</th>
                    <th class="px-4 py-2 text-left">Account Name</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($paymentMethods as $method)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $method->name }}</td>
                    <td class="px-4 py-2">{{ $method->account_number ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $method->account_name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $method->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('admin.payment-methods.edit', $method) }}" class="text-blue-600 hover:text-blue-800 mr-2">✏️ Edit</a>
                        <form action="{{ route('admin.payment-methods.destroy', $method) }}" method="POST" class="inline">
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
    {{ $paymentMethods->links() }}
</div>
@endsection