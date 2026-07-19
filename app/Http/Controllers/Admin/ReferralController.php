<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        $referrals = Referral::with(['referrer', 'referred'])->paginate(20);
        return view('admin.referrals.index', compact('referrals'));
    }

    public function destroy(Referral $referral)
    {
        $referral->delete();
        return redirect()->route('admin.referrals.index')->with('success', 'Referral deleted successfully.');
    }
}