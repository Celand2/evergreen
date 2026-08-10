<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EverGreen</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .primary-color {
            background-color: #15f112;
        }

        .accent-color {
            background-color: #fbbf24;
        }

        .form-field {
            position: relative;
        }

        .form-field input {
            width: 100%;
            height: 44px;
            padding: 0.75rem 0.85rem;
            margin-bottom: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-field input.has-toggle {
            padding-right: 2.5rem;
        }

        .form-field input:focus {
            outline: none;
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(134, 239, 172, 0.15);
        }

        .form-field input::placeholder {
            color: transparent;
        }

        .form-field label {
            position: absolute;
            top: 0.85rem;
            left: 0.9rem;
            font-size: 0.82rem;
            color: #6b7280;
            background: #ffffff;
            padding: 0 0.25rem;
            pointer-events: none;
            transition: transform 0.2s ease, top 0.2s ease, font-size 0.2s ease, color 0.2s ease;
        }

        .form-field input:focus+label,
        .form-field input:not(:placeholder-shown)+label {
            top: -0.45rem;
            transform: translateY(0);
            font-size: 0.75rem;
            color: #16a34a;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 0.85rem;
            cursor: pointer;
            color: #9ca3af;
            background: none;
            border: none;
            padding: 4px;
            z-index: 10;
        }
        .toggle-password:hover { color: #22c55e; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold text-center mb-6 primary-color text-white py-3 rounded">EverGreen</h1>

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

            <div class="mb-4 form-field">
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Full Name"
                    class="border focus:outline-none focus:ring-2 focus:ring-green-500">
                <label for="name">Full Name</label>
            </div>

            <div class="mb-4 form-field">
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="Phone"
                    class="border focus:outline-none focus:ring-2 focus:ring-green-500">
                <label for="phone">Phone</label>
            </div>

            <div class="mb-4 form-field">
                <input type="text" name="country" id="country" value="{{ old('country') }}" required placeholder="Country"
                    class="border focus:outline-none focus:ring-2 focus:ring-green-500">
                <label for="country">Country</label>
            </div>

            <div class="mb-4 form-field">
                <input type="password" name="password" id="password" required placeholder="Password"
                    class="border has-toggle focus:outline-none focus:ring-2 focus:ring-green-500">
                <label for="password">Password</label>
                <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>

            <div class="mb-4 form-field">
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Confirm Password"
                    class="border has-toggle focus:outline-none focus:ring-2 focus:ring-green-500">
                <label for="password_confirmation">Confirm Password</label>
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>

            <div class="mb-6 form-field">
                <input type="text" name="referral_code" id="referral_code"
                    value="{{ old('referral_code', $referralCode ?? '') }}"
                    placeholder="Referral Code (Optional)"
                    {{ ($referralCode ?? old('referral_code')) ? 'readonly' : '' }}
                    class="border focus:outline-none focus:ring-2 focus:ring-green-500 {{ ($referralCode ?? old('referral_code')) ? 'bg-gray-100 text-gray-500' : '' }}">
                <label for="referral_code">Referral Code (Optional)</label>
                @if($referralCode ?? old('referral_code'))
                <p class="text-xs text-green-600 -mt-3 mb-3">✓ Referral code applied</p>
                @endif
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

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            btn.innerHTML = isHidden
                ? `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                   </svg>`
                : `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                   </svg>`;
        }
    </script>
</body>

</html>