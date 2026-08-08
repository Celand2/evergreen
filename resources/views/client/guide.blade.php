@extends('layouts.client')

@section('content')
<div class="client-section rounded-xl p-4 mb-4 border border-gray-700"
     style="background: radial-gradient(ellipse at top right, rgba(32,251,3,0.08) 0%, #1f2937 80%);">
    <div class="flex items-center justify-between gap-3 mb-4">
        <div>
            <p class="text-[10px] uppercase tracking-wider text-[#20fb03]">Guide</p>
            <h2 class="text-white text-lg font-semibold">The EverGreen Story: From London to the World</h2>
        </div>
        <a href="{{ route('client.dashboard') }}"
           class="text-[11px] text-[#20fb03] border border-[#20fb03]/40 rounded-lg px-3 py-1.5 hover:bg-[#20fb03] hover:text-gray-900 transition">
            Back to dashboard
        </a>
    </div>

    <div class="space-y-3 text-sm text-gray-300">
        <div class="rounded-lg border border-gray-700 bg-gray-900/60 p-3">
            <p class="font-semibold text-white mb-1">📜 The Origin (London, 2018)</p>
            <p class="text-gray-400 leading-relaxed">
                It all began in the heart of the City of London in 2018. Originally, EverGreen Capital started as an exclusive circle of financial technology enthusiasts and quantitative analysts. Their realization was simple: high-yield financial opportunities were strictly reserved for institutional funds, leaving everyday individuals behind.
            </p>
            <p class="text-gray-400 leading-relaxed mt-2">
                The name EverGreen draws inspiration from the timeless greenery of Kensington Gardens and the concept of “evergreen prosperity”—a portfolio engineered to withstand changing seasons and market volatility.
            </p>
        </div>

        <div class="rounded-lg border border-gray-700 bg-gray-900/60 p-3">
            <p class="font-semibold text-white mb-1">⏳ The Timeline</p>
            <div class="space-y-2 text-gray-400 leading-relaxed">
                <p><span class="text-[#20fb03]">March 14, 2018</span> — Launch of the pilot initiative in England. Initial development of algorithmic asset allocation systems and multi-tier network distribution mechanics.</p>
                <p><span class="text-[#20fb03]">September 22, 2020</span> — Introduction of the Super-Sponsor framework with a 3-tier commission structure (11% – 3% – 1%), designed to incentivize community expansion and directly redistribute value back to active members.</p>
                <p><span class="text-[#20fb03]">November 10, 2023</span> — Full digital transformation into a responsive web application, expanding accessibility across international regions.</p>
                <p><span class="text-[#20fb03]">2026</span> — Major strategic expansion across East Africa. Integration of regional East African currencies, optimized mobile integration, dynamic welcome incentives, and a seamless daily reward architecture built for the local market.</p>
            </div>
        </div>
    </div>
</div>
@endsection
