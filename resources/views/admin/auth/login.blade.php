<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - EverGreen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .compact-input {
            width: 100%;
            height: 44px;
            padding: 0.75rem 0.85rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .compact-input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(134, 239, 172, 0.15);
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">EverGreen Admin</h1>
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                    class="compact-input">
            </div>

            <div class="mb-5">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="compact-input">
            </div>

            <button type="submit" 
                class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-200">
                Login
            </button>
        </form>
    </div>
</body>
</html>