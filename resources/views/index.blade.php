<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EverGreen - Grow Your Money Daily</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @laravelPWA
    <style>
        :root { --primary: #a4fb03; }
        .text-primary { color: #a4fb03; }
        .bg-primary { background-color: #a4fb03; }
        .border-primary { border-color: #a4fb03; }
        .glow-card {
            border: 1px solid #374151;
            box-shadow: inset 0 0 12px rgba(164, 251, 3, 0.07);
            isolation: isolate;
            overflow: hidden;
            position: relative;
        }
        .glow-card::before {
            background: radial-gradient(ellipse at center, rgba(164, 251, 3, 0.07) 0%, rgba(164, 251, 3, 0.03) 55%, rgba(164, 251, 3, 0.01) 100%);
            content: '';
            inset: 0;
            pointer-events: none;
            position: absolute;
            z-index: 0;
        }
        .glow-card > * {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

    <!-- Hero Section -->
    <section class="flex-1 flex items-center justify-center px-4 py-12 sm:py-16">
        <div class="max-w-4xl w-full text-center">
            
            <!-- Logo -->
            <div class="flex items-center justify-center gap-2 mb-8">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-primary">EverGreen</h1>
            </div>

            <!-- Headline -->
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 leading-tight">
                Grow your money, every single day.
            </h2>

            <!-- Subheadline -->
            <p class="text-gray-400 text-base sm:text-lg mb-8 max-w-2xl mx-auto">
                Invest in VIP plans, earn daily returns, and withdraw to your local payment method.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <a href="{{ route('client.login') }}" 
                   class="w-full sm:w-auto px-8 py-3 rounded-lg border-2 border-primary text-primary font-semibold hover:bg-primary hover:text-gray-900 transition">
                    Log In
                </a>
                <a href="{{ route('client.register') }}" 
                   class="w-full sm:w-auto px-8 py-3 rounded-lg bg-primary text-gray-900 font-semibold hover:opacity-90 transition">
                    Get Started
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="px-4 py-12 sm:py-16 bg-gray-900">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-center mb-8">About EverGreen</h2>
            
            <p class="text-gray-300 text-center max-w-3xl mx-auto mb-10 leading-relaxed">
                EverGreen is a platform where users invest in VIP plans and earn daily returns. 
                Your earnings are paid out in your local currency, making it simple to track and manage your investments. 
                Invite others and earn commissions through our referral program.
            </p>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                
                <!-- Daily Earnings -->
                <div class="glow-card rounded-xl p-5 sm:p-6 text-center">
                    <svg class="w-10 h-10 text-primary mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3 0 3 3 5 3 5s3-2 3-5c0-1.657-1.343-3-3-3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3"/>
                    </svg>
                    <h3 class="text-white font-semibold text-base mb-2">Daily Earnings</h3>
                    <p class="text-gray-400 text-sm">Earn a percentage of your investment every day.</p>
                </div>

                <!-- Local Currency -->
                <div class="glow-card rounded-xl p-5 sm:p-6 text-center">
                    <svg class="w-10 h-10 text-primary mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3 0 3 3 5 3 5s3-2 3-5c0-1.657-1.343-3-3-3z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12"/>
                    </svg>
                    <h3 class="text-white font-semibold text-base mb-2">Local Currency</h3>
                    <p class="text-gray-400 text-sm">See your balance and transactions in your own currency.</p>
                </div>

                <!-- Referral Rewards -->
                <div class="glow-card rounded-xl p-5 sm:p-6 text-center">
                    <svg class="w-10 h-10 text-primary mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-white font-semibold text-base mb-2">Referral Rewards</h3>
                    <p class="text-gray-400 text-sm">Earn commissions by inviting others to join.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="px-4 py-12 sm:py-16 bg-gray-900">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-bold text-center mb-10">How it works</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-gray-900 font-bold text-xl mx-auto mb-4">
                        1
                    </div>
                    <h3 class="text-white font-semibold text-base mb-2">Create your account</h3>
                    <p class="text-gray-400 text-sm">Sign up in seconds</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-gray-900 font-bold text-xl mx-auto mb-4">
                        2
                    </div>
                    <h3 class="text-white font-semibold text-base mb-2">Choose a VIP plan</h3>
                    <p class="text-gray-400 text-sm">Invest and start earning</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-gray-900 font-bold text-xl mx-auto mb-4">
                        3
                    </div>
                    <h3 class="text-white font-semibold text-base mb-2">Withdraw anytime</h3>
                    <p class="text-gray-400 text-sm">Cash out your earnings to your local payment method</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="px-4 py-8 bg-gray-900 border-t border-gray-800">
        <div class="max-w-5xl mx-auto text-center">
            <div class="flex items-center justify-center gap-2 mb-3">
                <div class="w-6 h-6 bg-primary rounded flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-900" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-primary">EverGreen</span>
            </div>
            <p class="text-gray-500 text-xs">© 2024 EverGreen. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>