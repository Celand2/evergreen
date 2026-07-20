@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Plans VIP disponibles</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($vips as $vip)
            <div class="border rounded-lg p-6 hover:shadow-lg transition">
                <h3 class="text-xl font-bold mb-2">{{ $vip->name }}</h3>
                <p class="text-gray-600 mb-4">{{ $vip->description }}</p>

                <div class="mb-4">
                    <p class="text-3xl font-bold text-green-600">${{ number_format($vip->price, 2) }}</p>
                    <p class="text-sm text-gray-500">Investissement</p>
                    <p class="text-sm text-gray-500">{{ auth()->user()->toLocal($vip->price) }}</p>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Retour journalier :</span>
                        <span class="font-semibold">{{ $vip->daily_percentage }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durée :</span>
                        <span class="font-semibold">{{ $vip->duration_days }} jours</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Gain journalier :</span>
                            <span class="font-semibold text-green-600">${{ number_format($vip->calculateDailyGain($vip->price), 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Équivalent local :</span>
                            <span class="text-sm text-gray-500">{{ auth()->user()->toLocal($vip->calculateDailyGain($vip->price)) }}</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('client.vips.buy', $vip) }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition"
                        @if(auth()->user()->balance_investissable < $vip->price) disabled @endif>
                        @if(auth()->user()->balance_investissable < $vip->price)
                            Solde insuffisant
                        @else
                            ⭐ Acheter VIP
                        @endif
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection