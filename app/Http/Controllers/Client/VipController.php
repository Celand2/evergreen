<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Vip;
use App\Models\UserVip;
use Illuminate\Http\Request;

class VipController extends Controller
{
    public function plans()
    {
        $vips = Vip::where('is_active', true)->paginate(20);
        return view('client.vips.index', compact('vips'));
    }

    public function mine()
    {
        $user = auth()->user();
        $activeVips = $user->userVips()->active()->with('vip')->get();
        return view('client.vips.mine', compact('activeVips'));
    }

    public function buy(Request $request, Vip $vip)
    {
        $user = auth()->user();

        // Check if user has enough balance_investissable
        if ($user->balance_investissable < $vip->price) {
            return back()->with('error', 'Insufficient balance.');
        }

        // Deduct from balance_investissable
        $user->balance_investissable -= $vip->price;
        $user->save();

        // Create user_vip
        $startedAt = now();
        $expiresAt = now()->addDays($vip->duration_days);
        $dailyGain = $vip->calculateDailyGain($vip->price);

        UserVip::create([
            'user_id' => $user->id,
            'vip_id' => $vip->id,
            'amount_invested' => $vip->price,
            'daily_gain' => $dailyGain,
            'started_at' => $startedAt,
            'expires_at' => $expiresAt,
            'status' => 'active',
        ]);

        return redirect()->route('client.vips.mine')->with('success', 'VIP purchased successfully!');
    }
}