<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EverGreen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .primary-color { background-color: #53f110; }
        .accent-color { background-color: #fbbf24; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-3xl font-bold text-center mb-8 primary-color text-white py-4 rounded">EverGreen</h1>
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('client.login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <button type="submit" 
                class="w-full primary-color text-white font-bold py-2 px-4 rounded hover:opacity-90 transition duration-200">
                Login
            </button>
        </form>

        <p class="text-center mt-4 text-gray-600">
            Don't have an account? <a href="{{ route('client.register') }}" class="text-green-600 font-semibold">Register</a>
        </p>
    </div>
</body>
</html>