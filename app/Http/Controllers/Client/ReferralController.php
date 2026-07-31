<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Team members (unique users), by level
        $level1Users = User::where('referred_by', $user->id)->get();
        $level1Ids = $level1Users->pluck('id');

        $level2Users = User::whereIn('referred_by', $level1Ids)->get();
        $level2Ids = $level2Users->pluck('id');

        $level3Users = User::whereIn('referred_by', $level2Ids)->get();

        $team = [
            'level1' => $level1Users,
            'level2' => $level2Users,
            'level3' => $level3Users,
        ];

        // Real commission history (exclude activation markers with commission = 0)
        $commissions = Referral::where('referrer_id', $user->id)
            ->where('commission', '>', 0)
            ->with('referred')
            ->latest()
            ->get();

        $stats = [
            'level1_count'     => $level1Users->count(),
            'level2_count'     => $level2Users->count(),
            'level3_count'     => $level3Users->count(),
            'active_count'     => $level1Users->filter(fn ($u) => $u->qualifiesAsActive())->count(),
            'total_commission' => $commissions->sum('commission'),
        ];

        $currentTier = $user->currentSponsorTier();
        $nextTier = $currentTier->nextTier();

        return view('client.referrals.index', compact('team', 'commissions', 'stats', 'currentTier', 'nextTier'));
    }
}