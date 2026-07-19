<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVip;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_vips' => UserVip::active()->count(),
            'pending_deposits' => Deposit::pending()->count(),
            'pending_withdrawals' => Withdrawal::pending()->count(),
            'total_balance_retirable' => User::sum('balance_retirable'),
            'total_balance_investissable' => User::sum('balance_investissable'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}