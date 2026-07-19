<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Services\DepositService;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with(['user', 'paymentMethod'])->paginate(20);
        return view('admin.deposits.index', compact('deposits'));
    }

    public function approve(Deposit $deposit)
    {
        app(DepositService::class)->approve($deposit);
        return back()->with('success', 'Deposit approved successfully.');
    }

    public function reject(Deposit $deposit)
    {
        $deposit->update(['status' => 'rejected']);
        return back()->with('success', 'Deposit rejected successfully.');
    }

    public function destroy(Deposit $deposit)
    {
        $deposit->delete();
        return redirect()->route('admin.deposits.index')->with('success', 'Deposit deleted successfully.');
    }
}