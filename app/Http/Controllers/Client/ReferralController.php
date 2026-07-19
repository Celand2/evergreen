<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $referrals = [
            'level1' => $user->referrals()->where('level', 1)->with('referred')->get(),
            'level2' => $user->referrals()->where('level', 2)->with('referred')->get(),
            'level3' => $user->referrals()->where('level', 3)->with('referred')->get(),
        ];

        $stats = [
            'level1_count' => $referrals['level1']->count(),
            'level2_count' => $referrals['level2']->count(),
            'level3_count' => $referrals['level3']->count(),
            'total_commission' => $user->referrals()->sum('commission'),
        ];

        return view('client.referrals.index', compact('referrals', 'stats'));
    }
}