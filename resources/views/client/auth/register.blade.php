<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EverGreen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .primary-color { background-color: #15f112; }
        .accent-color { background-color: #fbbf24; }
    </style>
      @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-3xl font-bold text-center mb-8 primary-color text-white py-4 rounded">EverGreen</h1>
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('client.register') }}">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email (Optional)</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-4">
                <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country</label>
                <input type="text" name="country" id="country" value="{{ old('country') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-6">
                <label for="referral_code" class="block text-gray-700 text-sm font-bold mb-2">Referral Code (Optional)</label>
                <input type="text" name="referral_code" id="referral_code" value="{{ old('referral_code') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit" 
                class="w-full primary-color text-white font-bold py-2 px-4 rounded hover:opacity-90 transition duration-200">
                Register
            </button>
        </form>

        <p class="text-center mt-4 text-gray-600">
            Already have an account? <a href="{{ route('client.login') }}" class="text-green-600 font-semibold">Login</a>
        </p>
    </div>
</body>
</html>