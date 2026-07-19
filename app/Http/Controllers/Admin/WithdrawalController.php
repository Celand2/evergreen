<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with(['user', 'paymentMethod'])->paginate(20);
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        app(WithdrawalService::class)->approve($withdrawal);
        return back()->with('success', 'Withdrawal approved successfully.');
    }

    public function reject(Withdrawal $withdrawal)
    {
        app(WithdrawalService::class)->reject($withdrawal);
        return back()->with('success', 'Withdrawal rejected and amount refunded.');
    }

    public function destroy(Withdrawal $withdrawal)
    {
        $withdrawal->delete();
        return redirect()->route('admin.withdrawals.index')->with('success', 'Withdrawal deleted successfully.');
    }
}