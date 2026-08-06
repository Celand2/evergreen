@extends('layouts.client')

@section('content')
<div class="bg-gray-800 rounded-xl shadow-lg p-6 text-white">
    <h2 class="text-2xl font-bold mb-6">My Profile</h2>
    
    <form action="{{ route('client.profile.update') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-300 text-sm font-bold mb-2">Full Name</label>
            <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

       

        <div class="mb-6">
            <label for="country" class="block text-gray-300 text-sm font-bold mb-2">Country</label>
            <input type="text" name="country" id="country" value="{{ auth()->user()->country }}" required
                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <button type="submit" 
            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition">
            Update Profile
        </button>
    </form>

    <div class="mt-8 pt-8 border-t border-gray-700">
        <h3 class="text-xl font-bold mb-4">Account Information</h3>
        <div class="space-y-3">
            <p class="text-gray-300"><span class="font-semibold text-white">Phone:</span> {{ auth()->user()->phone }}</p>
            <p class="text-gray-300"><span class="font-semibold text-white">Referral Code:</span> {{ auth()->user()->referral_code }}</p>
            <p class="text-gray-300"><span class="font-semibold text-white">Member Since:</span> {{ auth()->user()->created_at->format('Y-m-d') }}</p>
        </div>
    </div>

    <div class="mt-8 pt-8 border-t border-gray-700">
        <form action="{{ route('client.logout') }}" method="POST" onsubmit="return confirm('Are you sure you want to logout?')">
            @csrf
            <button type="submit" 
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
