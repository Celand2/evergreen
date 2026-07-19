<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserVip;
use App\Models\Notification;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $todayEarnings = $user->dailyGains()->whereDate('date', today())->sum('amount_usd')
            + ($user->hasCheckedInToday() ? 0.5 : 0);

        $totalEarnings = $user->dailyGains()->sum('amount_usd')
            + $user->checkIns()->sum('amount')
            + $user->referrals()->sum('commission');

        $latestActivity = \App\Models\Withdrawal::with('user')
            ->where('status', 'approved')
            ->latest()
            ->first();

        $data = [
             'user'                  => $user, 
            'balance_investissable' => $user->balance_investissable,
            'balance_retirable'     => $user->balance_retirable,
            'active_vips'           => $user->userVips()->active()->get(),
            'has_checked_in_today'  => $user->hasCheckedInToday(),
            'today_earnings'        => $todayEarnings,
            'total_earnings'        => $totalEarnings,
            'unread_count'          => $user->notifications()->unread()->count(),
            'latest_activity'       => $latestActivity,
        ];

        return view('client.dashboard', $data);
    }
}
