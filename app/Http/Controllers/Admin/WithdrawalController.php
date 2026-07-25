<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user', 'paymentMethod'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $withdrawals = $query->paginate(20)->withQueryString();

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
