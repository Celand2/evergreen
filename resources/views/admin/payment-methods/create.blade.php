@extends('layouts.admin')

@section('title', 'Create Payment Method')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Create Payment Method</h2>
    
    <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-4">
            <label for="account_number" class="block text-gray-700 text-sm font-bold mb-2">Account Number</label>
            <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-4">
            <label for="account_name" class="block text-gray-700 text-sm font-bold mb-2">Account Name</label>
            <input type="text" name="account_name" id="account_name" value="{{ old('account_name') }}"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-4">
            <label for="logo" class="block text-gray-700 text-sm font-bold mb-2">Logo</label>
            <input type="file" name="logo" id="logo" accept="image/*"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="mr-2">
                <span class="text-gray-700">Active</span>
            </label>
        </div>

        <button type="submit" 
            class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition">
            Create Payment Method
        </button>
    </form>
</div>
@endsection