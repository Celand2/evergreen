@extends('layouts.admin')

@section('title', 'Create VIP')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Create New VIP Plan</h2>
    
    <form action="{{ route('admin.vips.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" id="description" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price (USD)</label>
                <input type="number" name="price" id="price" step="0.01" value="{{ old('price') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label for="daily_percentage" class="block text-gray-700 text-sm font-bold mb-2">Daily Percentage (%)</label>
                <input type="number" name="daily_percentage" id="daily_percentage" step="0.01" value="{{ old('daily_percentage') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        <div class="mb-4">
            <label for="duration_days" class="block text-gray-700 text-sm font-bold mb-2">Duration (Days)</label>
            <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days') }}" required
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
            Create VIP
        </button>
    </form>
</div>
@endsection