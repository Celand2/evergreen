<?php

namespace App\Services;

use App\Models\LuckyWheelSegment;
use App\Models\LuckyWheelSpin;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class LuckyWheelService
{
    public function spin(User $user): LuckyWheelSpin
    {
        if (! $user->lucky_wheel_available) {
            throw ValidationException::withMessages([
                'wheel' => 'You do not have a spin available right now.',
            ]);
        }

        $segment = LuckyWheelSegment::active()->inRandomOrder()->firstOrFail();

        $spin = LuckyWheelSpin::create([
            'user_id'                => $user->id,
            'lucky_wheel_segment_id' => $segment->id,
            'amount_usd'             => $segment->amount_usd,
        ]);

        $user->balance_retirable += $segment->amount_usd;
        $user->lucky_wheel_available = false;
        $user->save();

        return $spin;
    }
}