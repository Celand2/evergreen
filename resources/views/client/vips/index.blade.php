@extends('layouts.client')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold mb-6">Available VIP Plans</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($vips as $vip)
            <div class="border rounded-lg p-6 hover:shadow-lg transition">
                <h3 class="text-xl font-bold mb-2">{{ $vip->name }}</h3>
                <p class="text-gray-600 mb-4">{{ $vip->description }}</p>

                <div class="mb-4">
                    <p class="text-3xl font-bold text-green-600">{{ auth()->user()->toLocal($vip->price) }}</p>
                    <p class="text-sm text-gray-500">Investment</p>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Daily return:</span>
                        <span class="font-semibold">{{ $vip->daily_percentage }}%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-semibold">{{ $vip->duration_days }} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Daily gain:</span>
                        <span class="font-semibold text-green-600">{{ auth()->user()->toLocal($vip->calculateDailyGain($vip->price)) }}</span>
                    </div>
                </div>

                <form action="{{ route('client.vips.buy', $vip) }}" method="POST">
                    @csrf
                    <button type="submit" 
                        class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition"
                        @if(auth()->user()->balance_investissable < $vip->price) disabled @endif>
                        @if(auth()->user()->balance_investissable < $vip->price)
                            Insufficient balance
                        @else
                            ⭐ Buy VIP
                        @endif
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection