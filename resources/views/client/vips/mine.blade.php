@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Mes VIPs</h2>

    @if($activeVips->isNotEmpty())
        <div class="grid grid-cols-1 gap-6">
            @foreach($activeVips as $userVip)
                <div class="border rounded-lg p-6 bg-gray-50 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold">{{ $userVip->vip->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $userVip->vip->description }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-green-600 text-white text-xs uppercase">Actif</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Investi :</span>
                                <span>${{ number_format($userVip->amount_invested, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Investi local :</span>
                                <span>{{ auth()->user()->toLocal($userVip->amount_invested) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Gain journalier :</span>
                                <span>${{ number_format($userVip->daily_gain, 2) }}</span>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Gain local :</span>
                                <span>{{ auth()->user()->toLocal($userVip->daily_gain) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Début :</span>
                                <span>{{ $userVip->started_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Expire :</span>
                                <span>{{ $userVip->expires_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Jours restants :</span>
                                <span>{{ now()->diffInDays($userVip->expires_at) }} jours</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="rounded-lg bg-yellow-50 border border-yellow-200 p-6 text-yellow-800">
            Vous n'avez pas encore de VIP actif. Allez dans l'onglet VIP pour choisir un plan.
        </div>
    @endif
</div>
@endsection
