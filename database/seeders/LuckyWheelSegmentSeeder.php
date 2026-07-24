<?php

namespace Database\Seeders;

use App\Models\LuckyWheelSegment;
use Illuminate\Database\Seeder;

class LuckyWheelSegmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([0.5, 1, 1.5, 2, 2.5] as $amount) {
            LuckyWheelSegment::create(['amount_usd' => $amount, 'is_active' => true]);
        }
    }
}