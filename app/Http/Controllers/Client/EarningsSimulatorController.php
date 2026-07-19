<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Vip;
use Illuminate\Http\Request;

class EarningsSimulatorController extends Controller
{
    public function index()
    {
        $vips = Vip::where('is_active', true)->get();
        return view('client.earnings-simulator', compact('vips'));
    }
}