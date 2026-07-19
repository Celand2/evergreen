<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        // Check if already checked in today
        if ($user->hasCheckedInToday()) {
            return back()->with('error', 'You have already checked in today.');
        }

        // Create check-in
        CheckIn::create([
            'user_id' => $user->id,
            'date' => today(),
            'amount' => 0.5,
        ]);

        // Add to balance_retirable
        $user->balance_retirable += 0.5;
        $user->save();

        return back()->with('success', 'Check-in successful! You earned $0.50.');
    }
}