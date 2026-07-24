<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\LuckyWheelService;
use Illuminate\Http\Request;

class LuckyWheelController extends Controller
{
    public function spin(Request $request, LuckyWheelService $service)
    {
        $spin = $service->spin(auth()->user());

        return back()->with('success', 'You won ' . auth()->user()->toLocal($spin->amount_usd) . '!');
    }
}